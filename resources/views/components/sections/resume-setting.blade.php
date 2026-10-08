<div class="d-flex border-bottom">
    <div>
        <div class="d-flex gap-2 justify-content-center align-content-center">
            <h4>Zoom</h4>
            <div class="btn-group">
                <button id="zoomIn" class="btn btn-light">+</button>
                <button id="zoomOut" class="btn btn-light">-</button>
            </div>
        </div>
    </div>
    <div>
        <div class="d-flex">
            <h4>Template</h4>
            <select id="templateSelect">
                <option value="modern">
                    Modern
                </option>

                <option value="professional">
                    Professional
                </option>
            </select>
        </div>
    </div>
    <div>
        <button id="downloadResume">Download PDF</button>
    </div>
</div>

@push('childScript')

<script>

</script>
@endpush
