<div class="{{$viewClass['form-group']}}">

    <label class="{{$viewClass['label']}} control-label">{!! $label !!}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <textarea class="form-control {{$class}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ $value }}</textarea>

        @include('admin::form.help-block')

    </div>
</div>

<script require="@tinymce" init="{!! $selector !!}">
    var opts = {!! admin_javascript_json($options) !!};

    opts.selector = '#'+id;

    if (! opts.init_instance_callback) {
        opts.init_instance_callback = function (editor) {
            editor.on('Change', function(e) {
                $this.val(String(e.target.getContent()).replace('<p><br data-mce-bogus="1"></p>', '').replace('<p><br></p>', ''));
            });
        }
    }

    opts['file_picker_callback'] = function (callback, value, meta) {
        // 主要判断 media
        if (meta.filetype === 'media') {
            // 动态创建上传input，并进行模拟点击上传操作，达到本地上传视频效果。
            let input = document.createElement('input');//创建一个隐藏的input
            input.setAttribute('type', 'file');
            input.setAttribute('id', 'tinymce_file_upload');
            input.setAttribute("accept", ".mp4");
            let that = this;
            input.onchange = function () {
                var formData = new FormData();
                formData.append("file", input.files[0]);
                formData.append("_token", '{{ csrf_token() }}');
                formData.append("dir", 'tinymce/videos');


                var url = "{{ asset('/admin/dcat-api/tinymce/upload') }}";
                var httpRequest = new XMLHttpRequest();
                httpRequest.open('POST', url);
                httpRequest.send(formData);

                // 响应后的回调函数
                httpRequest.onreadystatechange = function () {
                    if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                        var json = httpRequest.responseText;
                        // callback 回调的作用是将所选择的视频的url显示在输入框中
                        callback(JSON.parse(json).location);
                    }
                };
            }
            //触发点击
            input.click();
        }
    }
    tinymce.init(opts)
</script>
