@extends('admin.layouts.layout')

@section('content')
    <div class="card">
        <div class="header">
            <h2>インフォメーション管理</h2>


            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())

                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @include('flash::message')

        </div>
        <div class="body">
            {!! Form::open(['class' => 'form-horizontal', 'files' => true]) !!}
            <div class="table-responsive">

                <table class="table table-bordered table-hover table-striped table-condensed">

                    <tr>
                        <th class="text-center">タイトル<span class="attention">*</span></th>
                        <td class="text-center">
                            {!! Form::text('title', !empty($row->title) ? $row->title : null, ['required', 'class' => 'form-control', 'maxlength' => '1000']) !!}
                        </td>
                    </tr>


                    <tr>
                        <th class="text-center">公開日</th>
                        <td class="text-left">
                            <div class="col-xs-9 form-inline">
                                {!! Form::date('published_at', !empty($row->published_at) ? $row->published_at : null, ['class' => 'form-control', 'maxlength' => '1000']) !!}
                            </div>
                        </td>
                    </tr>


                    <tr>
                        <th class="text-center">直接リンク</th>
                        <td class="text-center">
                            {!! Form::text('url', !empty($row->url) ? $row->url : null, ['class' => 'form-control', 'maxlength' => '1000']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-center">PDF</th>
                        <td class="text-center">
                            {!! Form::file('pdf', null, ['class' => 'form-control']) !!}
                            @if(!empty($row->pdf))
                                <p>
                                    <a href="{{ \Config::get('directory.pdf_upload_url') }}{{$row->pdf}}" target="_blank">アップロード済みPDF</a>
                                </p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="text-center">画像</th>
                        <td class="text-center">
                            {!! Form::file('main_image', null, ['class' => 'form-control']) !!}
                            @if(!empty($row->main_image))
                                <img src="{{ \Config::get('directory.information_image_upload_url') }}{{$row->main_image}}"
                                     width="240">
                                <p>
                                    {!! Form::checkbox("deleteImage[main_image]", 1, false, ['class' => 'chk-col-blue', 'id' => 'deleteMainImage']) !!}
                                    <label for="deleteMainImage">画像を削除する</label>
                                </p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="text-center">内容</th>
                        <td class="text-center">
                            {!! Form::textarea('note', !empty($row->note) ? $row->note : null, ['class' => 'form-control ckeditor', 'maxlength' => '5000']) !!}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-center">内容(英語)</th>
                        <td class="text-center">
                            {!! Form::textarea('note_en', !empty($row->note_en) ? $row->note_en : null, ['class' => 'form-control ckeditor', 'maxlength' => '5000']) !!}
                        </td>
                    </tr>


                </table>
                <div class="text-center mb-25">
                    <a href="javascript:history.back();" class="btn btn-success"><span
                                class="glyphicon glyphicon-circle-arrow-left"></span>&nbsp;戻る</a>
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-ok"></span>&nbsp;登録
                    </button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>



@endsection

