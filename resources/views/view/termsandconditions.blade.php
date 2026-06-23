@include('view.layout.header')

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none text-success">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2 text-muted">/</li>
                    <li class="d-inline-block font-weight-bolder text-muted">Terms & Conditions</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<section class="privacy-content py-5" style="background-color: #f8fafc;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border ckeditor-content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</section>

@include('view.layout.footer')