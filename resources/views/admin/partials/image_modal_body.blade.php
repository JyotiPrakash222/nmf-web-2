<div class="row image_row">
      <input type="hidden" id="image_thumb_name">
            <input type="hidden" id="image_thumb_id">
    @foreach ($data['file'] as $file)
        <?php
        $imagePath = $file->full_path;
        if (strpos($imagePath, 'file') !== false) {
            $findFilePos = strpos($imagePath, 'file');
            $imageFilePath = substr($imagePath, $findFilePos);
            $imageFilePath = $imageFilePath . '/' . $file->file_name;
        }
        ?>
        <div class="col-md-2 popup" style="overflow: hidden;">
            <img style="width: 100%;" class="image_sec"
                 data-name="{{ $file->file_name }}" data-id="{{ $file->id }}"
                 src="{{ asset($imageFilePath) }}" />
            {{ $file->file_name }}
        </div>
    @endforeach
</div>

<div class="card-footer clearfix">
    <!-- {!! $data['file']->appends(['modal' => true])->links() !!} -->
     {!! $data['file']->appends(['modal' => true])->links('pagination::bootstrap-4') !!}
</div>
