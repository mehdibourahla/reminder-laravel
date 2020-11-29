@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Message</div>

                <div class="card-body">
                    <form method="post" id="edit_message" enctype="multipart/form-data" action="/m/{{$message->id}}">
                        @csrf
                        @method('PATCH')

                        <div class="form-group row">
                            <label for="caption" class="col-md-4 col-form-label">Message caption</label>

                            <input id="caption" name="caption" type="text"
                                class="form-control @error('caption') is-invalid @enderror"
                                value="{{ old('caption') ?? $message->caption }}" autocomplete="caption" autofocus>

                            @error('caption')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="description" class="col-md-4 col-form-label">Message description</label>

                            <textarea id="description" name="description" rows="10" form="edit_message"
                                class="form-control @error('description') is-invalid @enderror"
                                description="description" autocomplete="description"
                                autofocus>{{ old('description') ?? $message->description }}</textarea>

                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="tag" class="col-md-4 col-form-label">Tags<small class="text-muted"> * Separate
                                    tags with a
                                    comma.</small></label>

                            <input id="hashtags" name="tag" type="text"
                                class="form-control @error('tag') is-invalid @enderror"
                                value="{{ old('tag') ?? $tags, }}" autocomplete="off" autofocus>
                            <div class="tag-container">
                            </div>

                            @error('tag')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row pt-4">
                            <button class="btn btn-primary">Save Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', init, false);
function init(){
let input = document.querySelector('#hashtags');
let container = document.querySelector('.tag-container');
let hashtagArray = [];
message();
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
  let form = document.querySelector("#edit_message");
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