@extends('admin.layouts.account')

@php
    $description = (isset($author->description)) ? $author->description : ' ';
@endphp

@section('title', 'Autor: ' . $author->name . ' - Admin Amolca')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/single-author.css') }}">
<link rel="stylesheet" href="{{ asset('libs/select2/css/select2.min.css') }}">
@endsection

@section('scripts')
<script src='https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=1icfygu7db6ym5ibmufjkk2myppelx6v827sc9rq8xt1eo2n'></script>
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/admin/authors/single.js') }}"></script>
@endsection

@section('contentClass', 'single single-author')
@section('content')

    <div class="loader top hidde">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large green save-resource">
            <span class="icon-save1"></span>
        </a>
        <a class="btn-floating btn-large red go-all-resources" href="/am-admin/autores">
            <span class="icon-cross"></span>
        </a>
    </div>

    <div class="row single section-header valign-wrapper">
		<div class="col s12 m10 l10">
			<p class="title"> {{$author->name}} </p>
		</div>
		<div class="col s12 m2 l2 actions">
            <a class="btn-floating btn-large green save-resource">
                <span class="icon-save1"></span>
            </a>
            <a class="btn-floating btn-large red go-all-resources" href="/am-admin/autores">
                <span class="icon-cross"></span>
            </a>
		</div>
	</div>

    <form class="author-form" id="author-form" enctype="multipart/form-data">

        <input type="hidden" id="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="_id" value="{{ $author->_id }}">
        <input type="hidden" id="_src" value="authors">

        <ul class="tabs top-tabs">
            <li class="tab">
                <a class="active" href="#ajustes-basicos">Ajustes básicos</a>
            </li>
            <li class="tab">
                <a href="#especialidades">Especialidades</a>
            </li>
            <li class="tab">
                <a href="#seo">SEO</a>
            </li>
        </ul>

        <div id="ajustes-basicos" class="content-tabs">

            <div class="row">

                <div class="col s12 m5 col-image">

                    @if (isset($author->image))
                        <img id="resource-image" src="{{ $author->image }}" alt="">
                        <input type="hidden" id="image-url" name="image-url" value="{{ $author->image }}">
                    @else
                        <img id="resource-image" src="https://amolca.webussines.com/uploads/authors/no-author-image.png" alt="">
                        <input type="hidden" id="image-url" name="image-url">
                    @endif

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

                    <div class="global-upload-wrap">

                        <p id="image-error" class="error"></p>

                        <p class="desc">
                            El archivo debe pesar menos de 25mb.<br/>
                            Las medidas recomendadas son <b>250 x 250</b> (en pixeles).
                        </p>

                        <input type="button" id="save-file-btn" class="save" value="Guardar imagen">

                        <div class="file-upload-wrapper">
                            <button id="upload-file-btn" class="upload">Modificar imagen</button>
                            <input type="file" id="image" name="image">
                        </div>
                    </div>

                </div>

                <div class="col s12 m7">

                    <div class="form-group col s12 m12">
                        <label for="name"><span class="required">*</span> Nombre del autor:</label>
                        <input type="text" name="name" id="name" value="{{ $author->name }}">
                    </div>

                    <div class="form-group col s12 m12">
                        <label for="description">Descripción:</label>
                        <textarea name="description" id="description">{{ $description }}</textarea>
                    </div>

                </div>
                
            </div>

        </div>

        <div id="especialidades" class="content-tabs">
            <div class="row specialties">
                @foreach ($specialties as $specialty)
                    @if ($specialty->top)

                        <div class="col s12 m6">
                            <div class="form-group parent">

                                <label for="specialty-{{$specialty->_id}}">
                                    @php $checked = ''; @endphp
                                    
                                    @foreach ($author->specialty as $selected)
                                        @php
                                            if($selected->_id == $specialty->_id){
                                                $checked = 'checked="checked"';
                                            }
                                        @endphp
                                    @endforeach

                                    <input type="checkbox" name="specialty" id="specialty-{{$specialty->_id}}"  {{$checked}} value="{{$specialty->_id}}">

                                    <span>{{$specialty->title}}</span>
                                </label>

                            </div>

                            <div class="childs">
                                
                                @foreach ($specialty->childs as $child)

                                    <div class="form-group col s6 m6">
                                        <label for="specialty-{{$child->_id}}">
                                            @php $checked = ''; @endphp
                                            
                                            @foreach ($author->specialty as $selected)
                                                @php
                                                    if($selected->_id == $child->_id){
                                                        $checked = 'checked="checked"';
                                                    }
                                                @endphp
                                            @endforeach

                                            <input type="checkbox" name="specialty" id="specialty-{{$child->_id}}"  {{$checked}} value="{{$child->_id}}">

                                            <span>{{$child->title}}</span>
                                        </label>
                                    </div>
                                    
                                @endforeach

                            </div>
                        </div>

                    @endif
                @endforeach
            </div>
        </div>

        <div id="seo" class="content-tabs">
            
            <div class="row valign-wrapper">
                <div class="col s12 m4">
                    <p class="subtitle">Meta titulo:</p>
                </div>

                <div class="form-group col s12 m8">
                    <label for="meta-title">Meta titulo:</label>
                    <input type="text" id="meta-title" name="meta-title" placeholder="Meta titulo del autor..." value="@if (isset($author->metaTitle)) {{$author->metaTitle}} @endif">
                </div>
            </div>

            <div class="row valign-wrapper">
                <div class="col s12 m4">
                    <p class="subtitle">Meta descripción:</p>
                </div>

                <div class="form-group col s12 m8">
                    <label for="meta-description">Meta descripción:</label>
                    <textarea rows="3" id="meta-description" name="meta-description" placeholder="Meta descripción del autor...">@if (isset($author->metaDescription)) {{$author->metaDescription}} @endif</textarea>
                </div>
            </div>

            <div class="row valign-wrapper">
                <div class="col s12 m4">
                    <p class="subtitle">Meta etiquetas:</p>
                </div>

                <div class="form-group col s12 m8">
                    <label for="meta-tags">Meta etiquetas:</label>
                    
                        @if (isset($author->metaTags) && count($author->metaTags) > 0)
                            <textarea rows="3" id="meta-tags" name="meta-tags" placeholder="Meta etiquetas del autor...">@foreach ($author->metaTags as $tag)
                                {{$tag}}
                            @endforeach</textarea>
                        @else
                            <textarea rows="3" id="meta-tags" name="meta-tags" placeholder="Separar cada etiqueta con una comma ( , )..."></textarea>
                        @endif
                </div>
            </div>

        </div>

    </form>
@endsection