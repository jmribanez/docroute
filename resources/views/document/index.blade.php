@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- 1st Panel Navigation --}}
        <div class="col-md-2 d-flex flex-column">
            <div class="btn-group" role="group">
                <a href="{{route('document.create')}}" class="btn btn-primary"><i class="bi bi-file-earmark-plus-fill"></i> New</a>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('template.index')}}">From Template</a></li>
                    </ul>
                </div>
            </div>
            @can('list all documents')
            @if(!$showAll)
            <a href="?all=1" class="btn btn-outline-secondary mt-3">Show All</a>
            @else
            <a href="{{route('document.index')}}" class="btn btn-outline-secondary mt-3">Show Mine</a>
            @endif
            @endcan
        </div>
        {{-- 2nd Panel Index --}}
        <div class="col-md-4">
            @if(count($documents)>0)
            <ul class="list-group">
                @foreach ($documents as $document)
                <a href="{{route('document.show',$document->document->id)}}" class="list-group-item list-group-item-action">
                    <p class="mb-0 fw-bold">{{$document->document->title}}</p>
                    <p class="mb-0 small">Created by: {{$document->document->user->name_first . " " . $document->document->user->name_family . " on " . $document->document->created_at}}</p>
                </a>
                @endforeach
            </ul>
            <div class="my-1">
                {!!$documents->links()!!}
            </div>
            @else
            <div class="alert alert-secondary" role="alert">
                There are no documents.
            </div>
            @endif
        </div>
        {{-- 3rd Panel Details --}}
        <div class="col-md-6 d-flex flex-column">
            <div class="flex-grow-1 border rounded p-3">
                @include('inc.message')
                @switch($mode)
                    @case('index')
                        <x-document.noselection />
                        @break
                    @case('create')
                        <x-document.create />
                        @break
                    @case('show')
                        <x-document.show :document="$selectedDocument" :isUserInRoute="$isUserInRoute" :userCanEdit="$userCanEdit" />
                        @break
                    @default
                        
                @endswitch
            </div>
        </div>
    </div>
    
</div>
@endsection