@extends('layouts.app')

@section('title', 'Edit Product')
    
@section('content')
    <form action="{{ route('product.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <label for="product_name" class="form-label">Product Name</label>
        <div class="input-group mb-3">
            <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name', $product->name) }}" autofocus>
        </div>
        @error('product_name')
            <p class="text-danger small">{{ $message }}</p>
        @enderror

        <label for="price" class="form-label">Price</label>
        <div class="input-group mb-3">
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $product->price) }}" step="any" max="999.99">
        </div>
        @error('price')
            <p class="text-danger small">{{ $message }}</p>
        @enderror

        <label for="stock" class="form-label">Stock</label>
        <div class="input-group mb-3">
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
        </div>
        @error('stock')
            <p class="text-danger small">{{ $message }}</p>
        @enderror

        <label for="image" class="form-label">Image</label>
        <div class="input-group mb-1">
            <input type="file" name="image" id="image" class="form-control bg-white" aria-describedby="image-info">
        </div>
        <div id="image-info" class="form-text mb-2">
            Acceptable formats are jpeg, png, and gif only.
            Maximum file size is 1048KB.
        </div>
        @error('image')
            <p class="text-danger small">{{ $message }}</p>
        @enderror

        @if ($product->image)
            <img src="{{ asset('storage/images/'. $product->image) }}" alt="{{ $product->image }}" class="img-thmbnail d-flex mb-3" style="object-fit: cover; width: 250px">
        @endif


        <a href="{{ route('index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn btn-secondary">
            <i class="fa-solid fa-cheack"></i> Save
        </button>
    </form>
@endsection