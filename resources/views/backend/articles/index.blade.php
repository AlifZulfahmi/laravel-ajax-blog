@extends ('layouts.app')

@section('title') {{ __('Articles') }} @endsection

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <x-card icon="newspaper" title="Articles">
                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">Create</a>
                <div class="table-responsive">
                    <table class="table table-hover" id="yajra">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Tag</th>
                                <th>View</th>
                                <th width="10%">Status</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </x-card>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/backend/library/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="{{ asset('assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('assets/backend/js/article.js') }}"></script>

@endpush