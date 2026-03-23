@include('view.layout.header')

<div class="sp_header bg-white p-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul class="list-unstyled mb-0">
                    <li class="d-inline-block font-weight-bolder"><a href="{{ url('/') }}" class="text-decoration-none">home</a></li>
                    <li class="d-inline-block font-weight-bolder mx-2">/</li>
                    <li class="d-inline-block font-weight-bolder"><a href="#" class="text-decoration-none">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>


<section class="privacy-content">
    <div class="container">
        <div class="row">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</section>




@include('view.layout.footer')