@component('mail::message')
# Project deadline warning

This project will be ended in 40 days.<br>
Project No. : {{ $project->prj_no }}<br>
Project Name : {{ $project->prj_name }}<br>
Customer : {{ $project->customer }}<br>
Quo No. : {{ $project->quo_no }}<br>
Duration : {{ $project->prj_from }} - {{ $project->prj_to }}<br>

Thanks<br>
{{ config('app.name') }}
@endcomponent
