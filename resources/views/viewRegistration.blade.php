@extends('master')
@section('content')
<link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <div class="container-fluid bg-dark text-light py-3 mb-4 text-center">
        <div class="container">
            <h1 class="display-4">Registered Users</h1>
            <p class="lead">This Is Registred Users.</p>
        </div>
    </div>


    
    @if ($status == 200)
        <table id="example" class="hover table table-striped" >
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>&nbsp;Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->contactno }}</td>
                        <td>{{ $item->email }}</td>
                        
                        <td>
                            &nbsp;
                            <form method="POST" action="/delete_user/{{ $item->id }}" style="display: inline;">
                              @csrf
                              @method('DELETE')
                          
                              <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?')"> <i class="fas fa-trash-alt"></i> </button>
                          </form>
                          
                           
                          
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Contact No</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    @else
        <p>No data available.</p>
    @endif

        
<script src="plugins/jquery/jquery.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    @endsection

