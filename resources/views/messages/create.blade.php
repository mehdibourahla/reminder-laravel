@extends('layouts.app')

@section('content')
<div class="container">

    <form action="/m" enctype="multipart/form-data" method="post" id="create_message">
        @csrf
        <div class="row">
            <div class="col-8 offset-2">
                <div class="row">
                    <h1>Add New Message</h1>
                </div>
                <div class="form-group row">
                    <label for="caption" class="col-md-4 col-form-label">Message caption</label>

                    <input id="caption" name="caption" type="text"
                        class="form-control @error('caption') is-invalid @enderror" value="{{ old('caption') }}"
                        autocomplete="caption" autofocus>

                    @error('caption')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="description" class="col-md-4 col-form-label">Message description</label>

                    <textarea id="description" name="description" rows="10" form="create_message"
                        class="form-control @error('description') is-invalid @enderror" description="description"
                        autocomplete="description" autofocus>{{ old('description') }}</textarea>

                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group row">
                    <label for="tag" class="col-md-4 col-form-label">Tags <small class="text-muted"> * Separate
                            tags with a
                            comma.</small></label>

                    <input id="hashtags" name="tag" type="text" class="form-control @error('tag') is-invalid @enderror"
                        value="{{ old('tag') }}" autocomplete="off" autofocus>
                    <div class="tag-container"></div>

                    @error('tag')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group row">
                    <button class="btn btn-primary">Add New Message</button>
                </div>
            </div>
        </div>
    </form>


</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', init, false);
function init(){
let input = document.querySelector('#hashtags');
let container = document.querySelector('.tag-container');
let hashtagArray = [];
input.addEventListener('input', message, true);
  function message (event) {
    if (input.value.includes(',') && input.value.length > 1) {
        let tags = input.value.split(',');
        tags.forEach(tag => {
            if(tag.length >1){
                let text = document.createTextNode(tag);
                let p = document.createElement('p');

                p.appendChild(text);
                p.classList.add('tag');
                p.addEventListener('click', () => {
                    let index = Array.from(p.parentNode.children).indexOf(p);
                    hashtagArray.splice(index,1);
                    console.log(hashtagArray);
                    container.removeChild(p);
                    });

                container.appendChild(p);
                hashtagArray.push(tag);
            }
        });
      input.value = '';
    }
  }
  let form = document.querySelector("#create_message");
form.onsubmit = (e)=>{
    e.preventDefault();
    let keys = Object.keys(hashtagArray);
    let tag = document.createElement('input')
        tag.setAttribute('name','tags');
        tag.setAttribute('value',JSON.stringify(hashtagArray));
        tag.setAttribute('type','hidden');
        form.appendChild(tag);
    form.submit();
};
};
</script>
@endpush