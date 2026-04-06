{{-- Include once in your CMS admin sidebar, e.g. @include('course-module::cms.sidebar') --}}
@canany(['view-courses', 'view-course_forms', 'view-form_attributes'])
    <li class="nav-label">Course Module</li>
    @include('components.admin.menus.menu-item', ['route' => route('course-module.courses.index'), 'title' => 'Courses', 'icon' => 'fad fa-graduation-cap', 'permission' => 'view-courses', 'active' => request()->routeIs('course-module.courses.*')])
    @include('components.admin.menus.menu-item', ['route' => route('course-module.forms.index'), 'title' => 'Course Forms', 'icon' => 'fad fa-file-contract', 'permission' => 'view-course_forms', 'active' => request()->routeIs('course-module.forms.*')])
    @include('components.admin.menus.menu-item', ['route' => route('course-module.form-attributes.index'), 'title' => 'Form Attributes', 'icon' => 'fad fa-list-ol', 'permission' => 'view-form_attributes', 'active' => request()->routeIs('course-module.form-attributes.*')])
@endcanany
