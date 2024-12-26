<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Product</title>
  </head>
  <body>
    <div class="container mt-4">
        <div class="card">
            {{-- <div class="d-flex justify-content-between mb-3">

                <div>
                    <a href="{{ route('products.export') }}" class="btn btn-success">Export Products</a>
                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                        @csrf
                        <input type="file" name="file" class="form-control d-inline-block" style="width: auto;" required>
                        <button type="submit" class="btn btn-primary">Import Products</button>
                    </form>
                </div>
            </div> --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Product List</h5>
                <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->price }}</td>
                                <td>
                                    @if ($product->image)
                                        <img src="{{ asset($product->image) }}" alt="Product Image" width="50">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    @include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])

    </body>
</html>
