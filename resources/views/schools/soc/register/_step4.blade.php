<section class="space-y-6" aria-labelledby="step4-title">
    <h2 id="step4-title" class="font-serif text-xl font-semibold text-thc-navy sm:text-2xl">Parent / guardian</h2>

    <div class="grid gap-5 sm:grid-cols-2">
        @include('schools.soc.register._text', ['name' => 'parent_name', 'label' => 'Parent or guardian name', 'autocomplete' => 'name'])
        @include('schools.soc.register._text', ['name' => 'parent_relation', 'label' => 'Relation to applicant', 'autocomplete' => null])
        @include('schools.soc.register._textarea', ['name' => 'parent_address', 'label' => 'Parent / guardian address', 'rows' => 3])
        @include('schools.soc.register._text', ['name' => 'parent_telephone', 'label' => 'Parent / guardian telephone', 'autocomplete' => 'tel'])
        @include('schools.soc.register._text', ['name' => 'parent_email', 'label' => 'Parent / guardian email', 'type' => 'email', 'autocomplete' => 'email'])
        @include('schools.soc.register._text', ['name' => 'parent_mobile', 'label' => 'Parent / guardian mobile', 'autocomplete' => 'tel'])
    </div>
</section>
