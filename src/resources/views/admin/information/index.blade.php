@extends('admin.layouts.layout')

@section('content')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.js') }}"></script>

    <div class="card">
        <div class="header">
            <h2>インフォメーション管理</h2>
            <span>
                <a href="{{ route('admin.information.create') }}" class="btn btn-sm btn-success">
                    <span class="glyphicon glyphicon-edit"></span>&nbsp;新規登録
                </a>
            </span>

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

            <div class="table-responsive">
                {!! Form::open(['class' => 'form-horizontal', 'method' => 'GET']) !!}
                <table class="table table-bordered table-hover table-striped table-condensed">
                    <tr>
                        <th class="text-center" style="width: 20%">フリーワード</th>
                        <td class="text-center">
                            {!! Form::text('searchWord', null, ['class' => 'form-control', 'maxlength' => '50']) !!}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center" style="width: 20%">コンテンツ</th>
                        <td class="text-left">
                            {{Form::checkbox('information', 1, false, ['class' => 'chk-col-blue', 'id' => 'information'])}}
                            <label for="information">千代田区サイトに表示</label>　
                            {{Form::checkbox('sakura', 1, false, ['class' => 'chk-col-blue', 'id' => 'sakura'])}}
                            <label for="sakura">さくらまつりに表示</label>　
                        </td>
                    </tr>
                </table>

                <div align="center">
                    <button type="submit" class="btn btn-primary">
                        <span class="glyphicon glyphicon-ok"></span>&nbsp;検索
                    </button>
                </div>
                {!! Form::close() !!}


                <div class="table-responsive">
                    {!! Form::open(['class' => 'form-horizontal']) !!}
                    @if( $informations->count() > 0 )
                        {!! $informations->render() !!}
                        <div style="margin-bottom: 10px;">
                            <button type="submit" class="btn btn-warning" name="print" value="1">
                                <span class="glyphicon glyphicon-ok"></span>&nbsp;チェックしたものを削除する
                            </button>

                        </div>
                        <table class="table table-bordered table-hover table-striped table-condensed">
                            <tr>
                                <th>
                                    <script>
                                        $(function () {
                                            $('#item_all').on('click', function () {
                                                $('.item').prop('checked', this.checked);
                                            });

                                            $('.item').on('click', function () {
                                                if ($('#item :checked').length == $('#item :input').length) {
                                                    $('#item_all').prop('checked', 'checked');
                                                } else {
                                                    $('#item_all').prop('checked', false);
                                                }
                                            });
                                        });
                                    </script>
                                    <input type="checkbox" id="item_all" name="item_all" class="chk-col-red">
                                    <label for="item_all">全て</label>

                                </th>
                                <th>公開日</th>
                                <th>タイトル</th>
                                <th>表示</th>
                                <th>ステータス</th>
                                <th>操作</th>
                            </tr>
                            @foreach($informations as $information)
                                <tr>
                                    <td>
                                        {{Form::checkbox('ids[]', $information->id, false, ['class' => 'chk-col-blue item', 'id' => 'md_checkbox_'. $information->id])}}
                                        <label for="md_checkbox_{{$information->id}}"></label>
                                    </td>
                                    <td>{{$information->published_at}}</td>
                                    <td>
                                        @if ($information->emergency == 1)  <span style="color: red; font-weight: bold">【緊急】</span> @endif
                                        {{$information->title}}
                                    </td>
                                    <td>
                                        @if ($information->	information == 1) 千代田区サイト@endif
                                        @if ($information->	sakura == 1) さくらまつり@endif
                                    </td>
                                    <td>@if(!empty($information->status)) {{\Config::get('fixed.status')[$information->status]}} @else
                                            未公開 @endif</td>
                                    <td>
                                        @if ($information->status != 1 || empty($information->published_at))
                                            <a href="{{ route('admin.information.publish', $information->id) }}"
                                               class="btn btn-sm btn-edit">
                                                <span class="glyphicon glyphicon-edit"></span>&nbsp;公開する
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.information.edit', $information->id) }}"
                                           class="btn btn-sm btn-success">
                                            <span class="glyphicon glyphicon-edit"></span>&nbsp;編集
                                        </a>

                                        <a href="{{ route('admin.information.destroy', $information->id) }}"
                                           class="btn btn-sm btn-danger "
                                           onclick="if(!confirm('本当に削除しますか?')) return false;">
                                            <span class="glyphicon glyphicon-edit"></span>&nbsp;削除
                                        </a>

                                        <a href="{{ route('admin.information.copy', $information->id) }}"
                                           class="btn btn-sm btn-floating">
                                            <span class="glyphicon glyphicon-edit"></span>&nbsp;コピー
                                        </a>

                                        @if($information->type == 4)
                                            <a href="{{ route('information.preview', $information->id) }}"
                                               class="btn btn-sm btn-success" target="_blank">
                                                <span class="glyphicon glyphicon-edit"></span>&nbsp;プレビュー
                                            </a>

                                            <a href="{{ route('en.information.preview', $information->id) }}"
                                               class="btn btn-sm btn-success" target="_blank">
                                                <span class="glyphicon glyphicon-edit"></span>&nbsp;プレビュー(英語)
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        {!! $informations->render() !!}
                    @else
                        対応する一覧がありません。
                    @endif

                    {!! Form::close() !!}
                </div>
            </div>


        </div>


@endsection

