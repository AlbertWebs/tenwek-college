<?php

namespace App\Support\Admin;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

final class AdminActivityLogger
{
    /** @var list<string> */
    private const REDACT_KEYS = [
        'password',
        'password_confirmation',
        'current_password',
        'token',
        '_token',
        'otp',
        'secret',
        'api_key',
        'credit_card',
    ];

    public static function logFromRequest(Request $request, Response $response): void
    {
        if ($request->user() === null) {
            return;
        }

        if (! self::isAdminPanelRequest($request)) {
            return;
        }

        $route = $request->route();
        if ($route === null) {
            return;
        }

        $routeName = $route->getName() ?? '';

        [$auditableType, $auditableId] = self::resolveAuditable($request);

        $payload = [
            'method' => $request->method(),
            'path' => '/'.$request->path(),
            'route' => $routeName,
            'status' => $response->getStatusCode(),
            'parameters' => self::routeParametersForLog($request),
            'input' => self::redactedInput($request),
        ];

        AuditLog::query()->create([
            'user_id' => $request->user()->id,
            'action' => self::actionLabel($routeName, $request),
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => null,
            'new_values' => $payload,
            'ip_address' => $request->ip(),
        ]);
    }

    private static function isAdminPanelRequest(Request $request): bool
    {
        return $request->is('admin') || $request->is('admin/*');
    }

    private static function actionLabel(string $routeName, Request $request): string
    {
        if ($routeName !== '') {
            return mb_substr($routeName, 0, 191);
        }

        return mb_substr($request->method().' '.$request->path(), 0, 191);
    }

    /**
     * @return array{0: ?string, 1: ?int}
     */
    private static function resolveAuditable(Request $request): array
    {
        $route = $request->route();
        if ($route === null) {
            return [null, null];
        }

        foreach ($route->parameters() as $value) {
            if ($value instanceof Model) {
                return [$value->getMorphClass(), (int) $value->getKey()];
            }
        }

        return [null, null];
    }

    /**
     * @return array<string, mixed>
     */
    private static function routeParametersForLog(Request $request): array
    {
        $route = $request->route();
        if ($route === null) {
            return [];
        }

        $out = [];
        foreach ($route->parameters() as $key => $value) {
            if ($value instanceof Model) {
                $out[$key] = class_basename($value).':'.$value->getKey();
            } elseif (is_scalar($value) || $value === null) {
                $out[$key] = $value;
            }
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    private static function redactedInput(Request $request): array
    {
        $data = $request->except(self::REDACT_KEYS);

        return self::sanitizeForLog($data);
    }

    private static function sanitizeForLog(mixed $value): mixed
    {
        if ($value instanceof UploadedFile) {
            return '[uploaded_file:'.$value->getClientOriginalName().']';
        }

        if (is_array($value)) {
            $out = [];
            foreach ($value as $k => $v) {
                if (is_string($k) && in_array(mb_strtolower($k), self::REDACT_KEYS, true)) {
                    continue;
                }
                $out[$k] = self::sanitizeForLog($v);
            }

            return $out;
        }

        return $value;
    }
}
