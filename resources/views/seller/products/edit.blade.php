@extends('seller.sellerMain')

@section('styles')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/seller/products/create.css') }}">
@endsection

@section('content')
<div class="form-wrapper">
    <!-- Display success message -->
    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
            window.location.href = "{{ route('seller.products.list') }}";
        </script>
    @endif

    @if ($errors->any())
    <script>
        window.addEventListener('load', function() {
            let errorMessage = "";
            @foreach ($errors->all() as $error)
                errorMessage += "{{ addslashes($error) }}\n"; 
            @endforeach
            if (errorMessage) {
                alert(errorMessage.trim()); 
            }
        });
    </script>
    @endif

    <form action="{{ route('seller.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-4 bg-white shadow rounded">
        @csrf
        @method('PUT')

        <h2>Edit Products</h2>

        <!-- Product Name -->
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>

        <!-- Product Price -->
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" id="price" step="0.01" class="form-control" value="{{ old('price', $product->price) }}" required>
        </div>

        <!-- Product Image -->
        <div class="form-group">
            <label for="image">Product Image</label>
            <input type="file" name="image" id="image" class="form-control">
            @if($product->image)
                <div class="mt-2">
                    <img src="{{ asset($product->image) }}" alt="Current Image" width="100">
                </div>
            @endif
        </div>

        <!-- Product Type Selection -->
        <div class="form-group">
            <label for="product_type_id">Product Type</label>
            <select name="product_type_id" id="product_type_id" class="form-control" required>
                <option value="" disabled>Select Product Type</option>
                @foreach($productTypes as $type)
                    <option value="{{ $type->id }}" {{ $type->id == $product->product_type_id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Categories Selection (Checkboxes) -->
        <div class="form-group" id="categories-wrapper">
            <label>Categories</label>
            <div id="categories" class="d-flex flex-wrap">
                @foreach($categories as $category)
                    <label class="mr-2">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Attributes Selection (Dynamically Generated) -->
        <div id="attributes" class="form-group">
            @foreach($productAttributes as $attributeName => $attributeValue)
                <div class="form-group">
                    <label for="attribute_{{ $attributeName }}">{{ $attributeName }}</label>
                    <input type="text" name="attributes[{{ $attributeName }}]" id="attribute_{{ $attributeName }}"
                        class="form-control"
                        value="{{ old('attributes.' . $attributeName, $attributeValue) }}">
                </div>
            @endforeach
        </div>


        <!-- Stock -->
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Product</button>
    </form>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/seller/sellerProducts.js') }}"></script>
@endsection
