<div class="container">
            <div class="modal fade" id="order_code" tabindex="-1" role="dialog" aria-labelledby="orderCodeLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter Authorization Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form method="post">
                      <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Authorization code:</label>
                        <input type="text" class="form-control" id="waiter_code" name="waiter_code" required>
                      </div>
                      <?php echo form_error('waiter_code'); ?>
                      <input type="submit" class="btn btn-primary submit_order_code" value="Submit">

                    </form>
                  </div>

                </div>
              </div>
            </div>

</div>
<script type="text/javascript">
 $(window).load(function(){
   $('#order_code').modal('show');
    });
</script>
