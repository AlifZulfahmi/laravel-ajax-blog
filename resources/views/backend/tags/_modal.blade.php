<!-- Modal -->
<div class="modal fade" id="modalTag" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" id="formTag">
                    <div class="mb-3">
                        <input type="hidden" id="uuid" name="uuid" value="{{ $tag->uuid ?? '' }}">

                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="formTag" class="btn btn-primary btn-submit">Save</button>
            </div>
        </div>
    </div>
</div>