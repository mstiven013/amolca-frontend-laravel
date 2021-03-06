@extends('admin.layouts.account')

@php
    $title = (isset($post->title)) ? $post->title : '';
    $slug = (isset($post->slug)) ? $post->slug : '';
    $id = (isset($post->id)) ? $post->id : '';
    $thumbnail = (isset($post->thumbnail)) ? $post->thumbnail : 'https://amolca.webussines.com/uploads/images/no-image.jpg';
    $state = (isset($post->state)) ? $post->state : '';
    $content = (isset($post->content)) ? $post->content : '';
    $excerpt = (isset($post->excerpt)) ? $post->excerpt : '';

@endphp

@if ($title !== '')
    @section('title', 'Publicación: ' . $title . ' - Admin Amolca')
@else
    @section('title', 'Creando publicación nueva - Admin Amolca')
@endif

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/single-blog.css') }}">
<link rel="stylesheet" href="{{ asset('libs/select2/css/select2.min.css') }}">
@endsection

@section('scripts')
<script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=1icfygu7db6ym5ibmufjkk2myppelx6v827sc9rq8xt1eo2n'></script>
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/admin/slug-generator.js') }}"></script>
<script src="{{ asset('js/admin/blog/single.js') }}"></script>
@endsection

@section('contentClass', 'single single-specialty')
@section('content')

    <div class="loader top hidde">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <div class="row single section-header valign-wrapper">
        <div class="col s12 m10 l10">
            <p class="title"> @if ($title !== '') {!! $title !!} @else Creando nuevo libro @endif  </p>
        </div>
        <div class="col s12 m2 l2 actions">
            <a class="btn-floating btn-large green save-resource">
                <span class="icon-save1"></span>
            </a>
            <a class="btn-floating btn-large red go-all-resources" href="/am-admin/blog">
                <span class="icon-cross"></span>
            </a>
        </div>
    </div>

    <form id="book-edit" class="book-edit">
        <input type="hidden" id="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="_action" value="{{ $action }}">
        <input type="hidden" id="_src" value="books">
        <input type="hidden" id="_user" value="{{ session('user')->id }}">
        <input type="hidden" id="id" value="{{ $id }}">

        <ul class="tabs top-tabs">
            <li class="tab">
                <a class="active" href="#ajustes-basicos">Ajustes básicos</a>
            </li>
        </ul>

        <div id="ajustes-basicos" class="content-tabs">
            <div class="row">
                
                <div class="col s12 m8">

                    <div class="form-group col s12">
                        <label for="title"><span class="required">*</span> Título de la publicación:</label>
                        <input type="text" name="title" id="title" class="required-field" placeholder="Título del libro..." value="{!! $title !!}">
                    </div>

                    <div class="form-group col s12 m12">
                        <span id="slug">{{Request::root()}}/<span>{!! $slug !!}</span></span>
                    </div>
                    
                    <div class="form-group col s12">
                        <label for="content">Contenido:</label>
                        <textarea name="content" id="content" class="common-editor">{!! $content !!}</textarea>
                    </div>

                </div>

                <div class="col s12 m4">

                    <div class="meta-box">
                        <p class="title">Estado</p>

                        <div class="form-group">
                            <select name="state" id="state" class="normal-select">
                                <option @if ($state == 'PUBLISHED') selected @endif value="PUBLISHED">Publicado</option>
                                <option @if ($state == 'DRAFT') selected @endif value="DRAFT">Borrador</option>
                                <option @if ($state == 'TRASH') selected @endif value="TRASH">En papelera</option>
                            </select>
                        </div>
                    </div>

                    <div class="meta-box">
                        <div class="form-group">
                            <p class="title">Texto de introducción</p>
                            <textarea name="excerpt" id="excerpt">{!! $excerpt !!}</textarea>
                        </div>
                    </div>

                    <div class="meta-box">
                        <p class="title">Imagen del post</p>

                        <div class="image-wrap">
                            <img id="resource-image" src="{{ $thumbnail }}" alt="">
                            <input type="hidden" id="image-url" name="image-url" value="{{ $thumbnail }}">

                            <div class="circle-preloader preloader-wrapper big active">
                                <div class="spinner-layer spinner-green-only">
                                    <div class="circle-clipper left">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="gap-patch">
                                        <div class="circle"></div>
                                    </div>
                                    <div class="circle-clipper right">
                                        <div class="circle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="global-upload-wrap">

                            <p id="image-error" class="error"></p>

                            <p class="desc">
                                El archivo debe pesar menos de 25mb.<br/>
                                Las medidas recomendadas son <b>250 x 250</b> (en pixeles).
                            </p>

                            <input type="button" id="save-file-btn" class="save" value="Guardar imagen">

                            <div class="file-upload-wrapper">
                                <button id="upload-file-btn" class="upload">Seleccionar imagen</button>
                                <input type="file" id="image" name="image">
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="single-navigation">

            @if (isset($prev))
                <a class="btn-navigation previous" href="/am-admin/blog/{{$prev}}"><span class="icon-arrow-left2"></span> Anterior</a>
            @endif

            @if (isset($next))
                <a class="btn-navigation next" href="/am-admin/blog/{{$next}}">Siguiente <span class="icon-arrow-right2"></span></a>
            @endif

        </div>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large green save-resource">
                <span class="icon-save1"></span>
            </a>
            <a class="btn-floating btn-large red go-all-resources" href="/am-admin/blog">
                <span class="icon-cross"></span>
            </a>
        </div>

    </form>
@endsection