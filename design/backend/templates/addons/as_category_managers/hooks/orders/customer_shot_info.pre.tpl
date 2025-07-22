{*********************************************************
{if $auth.is_cm_user == "Y"}
<div class="well orders-right-pane">
    {include file="common/subheader.tpl" title="PO Document"}
    <div style="margin-bottom: 10px;"></div>
    {if $order_info.po_document_path}
        <p>
            <span class="cs-icon icon-file"></span>
            <a href="{"orders.download_po_document?order_id=`$order_info.order_id`"|fn_url}" target="_blank">{basename($order_info.po_document_path)}</a>
        </p>
        <a class="cm-dialog-opener cm-dialog-auto-size btn" data-ca-target-id="upload_po_document">Change document</a>
        <a href="{"orders.delete_po_document?order_id=`$order_info.order_id`"|fn_url}" class="cm-post cm-confirm btn">{__('delete')}</a>
    {else}
        <a class="cm-dialog-opener cm-dialog-auto-size btn" data-ca-target-id="upload_po_document">Upload document</a>
    {/if}
</div>

<div class="hidden" title="Upload PO document" id="upload_po_document">
    <form action="{""|fn_url}" method="POST" name="po_document_form" class="form-horizontal form-edit" enctype="multipart/form-data">
        <div class="control-group">
            <label for="po_document" class="control-label cm-required">{__("as_rfq_backorder.document")}:</label>
            <div class="controls">
                {include file="common/fileuploader.tpl" var_name="po_document" allowed_ext="doc,docx,pdf,jpg,jpeg,png"}
            </div>
        </div>

        <div class="modal-footer buttons-container">
            <div class="pull-right">
                <a class="cm-dialog-closer cm-cancel tool-link btn bulkedit-unchanged" data-dismiss="modal">{__("cancel")}</a>
                {include file="buttons/save.tpl" but_name="dispatch[orders.upload_po_document]" but_role="submit-link" save=$id but_target_form="po_document_form"}
            </div>
        </div>
    </form>
</div>
{/if}
**********************************************************}