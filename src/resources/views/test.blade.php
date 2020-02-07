@extends('layouts.app')

@section('content')

    <span class="pager_txt">{{ $products->total() }}件中、{{$products->firstItem()}}〜{{$products->lastItem()}}件を表示</span>

<ul>
@foreach($products as $product)
        <li>{{ $product->name }} / {{ $product->price }}円</li>
    @endforeach
</ul>

{!! $products->render() !!}

@endsection