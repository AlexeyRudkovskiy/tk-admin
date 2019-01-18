<div class="form-group file-type-container @if($file === null) not-selected @else uploaded @endif">
    <label class="form-control-label">{{ $label }}</label>
    <input name="{{ $name }}" type="hidden" @if($file !== null) value="{{ $file->id }}" @endif />
    <div class="admin-file-control">
        <div class="upload-file">
            <i class="fa fa-upload icon"></i><span>{{ trans('@admin::dashboard.field.file.upload') }}</span>
            <input type="file" name="{{ $name }}_input_file" />
        </div>
        <div class="select-file">
            <i class="fa fa-paperclip icon"></i><span>{{ trans('@admin::dashboard.field.file.select') }}</span>
        </div>
    </div>
    <div class="current-file">
        <div class="preview">
            @if($file !== null)
            <img src="{{ $file->getFullPathForThumbnail('150x150') ?? "" }}" />
            @else
            <img />
            @endif
        </div>
        <div class="preview-description">
            <div class="preview-filename">{{ $file->original_name or "" }}</div>
            <div class="preview-status">Загрузка...</div>
            <div class="preview-status-progress">
                <div class="progress progress-animated">
                    <div class="progress-bar progress-bar-primary" style="width: 0%;"></div>
                </div>
            </div>
            <div class="preview-meta">
                <span><span class="meta-label">Размер</span> <span class="meta-size-value">{{ $file->size or "" }}</span></span>
            </div>
            <div class="preview-actions">
                <a href="javascript:" class="preview-delete">убрать</a>
            </div>
        </div>
    </div>
</div>