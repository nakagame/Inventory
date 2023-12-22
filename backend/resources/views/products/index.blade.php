@extends('layouts.app')

@section('title', 'Product')
    
@section('content')    
    @if (Auth::user()->role !== 1)
        <div class="card">
            <div class="card-header">
                <h1 class="h3 fw-bold mb-0">New Product</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control bg-white" id="product_name" name="product_name" placeholder="Product Name" value="{{ old('product_name') }}" autofocus>
                        <label for="product_name">Product Name</label>
                        @error('product_name')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
        
                    <div class="form-floating mb-3">
                        <input type="number" name="price" id="price" class="form-control bg-white" placeholder="Input per price" value="{{ old('price') }}" step="any" max="999.99">
                        <label for="price" class="form-label">Price</label>
                        @error('price')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
        
                    <div class="form-floating mb-3">
                        <input type="number" name="stock" id="stock" class="form-control bg-white" placeholder="Stock" value="{{ old('stock') }}">
                        <label for="stock" class="form-label">Stock</label>
                        @error('stock')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
        
                    <div class="form-floating mb-3">
                        <input type="file" name="image" id="image" class="form-control bg-white" aria-describedby="image-info">
                        <label for="image" class="form-label">Image</label>
                        
                        <div id="image-info" class="form-text mt-0">
                            Acceptable formats are jpeg, png, and gif only.
                            Maximum file size is 1048KB.
                        </div>
                        @error('image')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    @if (Auth::user()->role === 1)
                        {{--  User --}}
                        <button type="submit" class="btn btn-primary" disabled>
                            <i class="fa-solid fa-plus"></i> Add
                        </button>
                    @else
                        {{--  User --}}
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Add
                        </button>
                    @endif
                    
                </form>
            </div>
        </div>
    @endif
   
    <table class="table table-hover align-middle table-sm mt-4">
        <thead class="table-secondary">
            <tr>
                <th>PHOTO</th>
                <th>PRODUCT NAME</th>
                <th>STOCK</th>
                <th>PRICE</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($all_products as $product)
                <tr>
                    <td>
                        @if ($product->image)
                            <img src="{{ asset('storage/images/'. $product->image) }}" alt="{{ $product->image }}" style="width: 50px; height: 50px; object-fit:contain;">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>$ {{ $product->price }}</td>
                    <td>
                        <div class="d-flex">
                            @if (Auth::user()->role != 1)
                                {{-- admin --}}
                                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-secondary btn-sm me-2">Edit</a>

                                <form action="{{ route('product.destroy', $product->id) }}" method="post" class="w-25">
                                    @csrf
                                    @method('DELETE')
    
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </div>    
                    </td>
                    <td>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#product-buy-{{ $product->id }}">
                            Buy
                        </button>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="product-buy-{{ $product->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="product-buy-{{ $product->id }}Label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-4 fw-bold text-primary" id="staticBackdropLabel">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                            PAYMENT
                                        </h1>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col d-flex align-items-center">
                                                <h2 class="h4 mb-0 me-2">{{ $product->name }}</h2>
                                                <div class="text-muted small">(Product ID: {{ $product->id }})</div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-3">
                                                <p class="fst-italic fq-bold mb-0">
                                                    Price: ${{ $product->price }}
                                                </p>
                                            </div>

                                        </div>

                                        <div class="row mt-3">
                                            <div class="col">
                                                <p class="h4 text-primary">Total Amount: $<span id="total_amount_{{ $product->id }}"></span></p>
                                            </div>
                                        </div>

                                        <form action="{{ route('product.updateStock', $product->id) }}" method="post">
                                            @csrf
                                            @method('PATCH')

                                            <label for="qty" class="form-label">Qty</label>
                                            <div class="input-group">
                                                <input type="number" name="qty" id="qty_{{ $product->id }}" class="form-control" value="{{ old('qty') }}" min="1" max="{{ $product->stock }}" aria-describedby="info-stock" required>
                                            </div>
                                            <div class="text-muted mb-3" id="info-stock">Stock: {{ $product->stock }}</div>
                                            @error('qty_{{ $product->id }}')
                                                <p class="text-danger small">{{ $message }}</p>
                                            @enderror

                                            <label for="total" class="form-label">Total</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">$</span>
                                                <input type="number" name="total" id="total_{{ $product->id }}" class="form-control" value="{{ old('total') }}" min="1" required>
                                            </div>

                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary" id="payButton_{{ $product->id }}">PAY</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No item...</td>
                </tr>
            @endforelse
        </tbody>
    </table>
        
    @isset($product)
        <script>
            $(document).ready(function () {
                function calculateTotal(productId, pricePerUnit) {
                    var qty = $('#qty_' + productId).val();

                    // Perform the calculation
                    var total = qty * pricePerUnit;

                    // Update the total field
                    $('#total_amount_' + productId).text(total.toFixed(2));

                    // Get the input amount
                    var inputAmount = parseFloat($('#total_' + productId).val()) || 0;

                    // Disable the 'PAY' button if the total is greater than the input amount
                    if (total > inputAmount) {
                        $('#payButton_' + productId).prop('disabled', true);
                    } else {
                        $('#payButton_' + productId).prop('disabled', false);
                    }
                }

                // Call the function on page load for each product modal
                @foreach($all_products as $product)
                    calculateTotal({{ $product->id }}, {{ $product->price }});
                @endforeach

                // Bind the function to the 'input' event of the quantity and total fields for each product modal
                @foreach($all_products as $product)
                    $('#qty_{{ $product->id }}, #total_{{ $product->id }}').on('input', function () {
                        calculateTotal({{ $product->id }}, {{ $product->price }});
                    });
                @endforeach
            });
        </script>
    @endisset    
@endsection