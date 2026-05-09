<?php

namespace App\Policies;

use App\Models\Download;
use App\Models\User;

class DownloadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'soc_admin', 'cohs_admin']);
    }

    public function view(User $user, Download $download): bool
    {
        return $this->managesDownloadSchool($user, $download);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'soc_admin', 'cohs_admin']);
    }

    public function update(User $user, Download $download): bool
    {
        return $this->managesDownloadSchool($user, $download);
    }

    public function delete(User $user, Download $download): bool
    {
        return $this->managesDownloadSchool($user, $download);
    }

    protected function managesDownloadSchool(User $user, Download $download): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        $school = $download->school;

        return $school !== null && $user->managesSchool($school);
    }
}
