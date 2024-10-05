    <div class="contaiter-fluid card-body bd-highlight cardbody" style="background-color:#fff">
        <div class="form-container  form-check" >
            <form method="post" action="modul/qlychatlieu/xly.php">
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm Tin Tức</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div class="form-group  py-3 ">
                  <label for="" class="col-form-label"><strong>Tên Tin Tức:</strong></label>
                  <input class="col-form-label mx-3" type="text" name="chatlieu" placeholder="Nhập Tên Tin Tức" width="300px" id="txttintuc"required>
                </div>
                <td valign="top" >Nội dung : </td>
			<td style="min-width: 100%; min-height: 100%;">
				<script type="text/javascript">
				  tinymce.init({
					selector: '#noi_dung',
					theme: 'modern',
		
					plugins: [
					  'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
					  'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
					  'save table contextmenu directionality emoticons template paste textcolor jbimages'
					],
					toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons jbimages',
					relative_urls: false
				  });
				  
				  </script>
				  <textarea id="noi_dung" name="noi_dung" ></textarea>
			</td>
			</td>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <input class="btn btn-primary mx-3" type="submit" name="themtintuc" value="Thêm ">
                    </div>
                    </div>
                </div>
                </div>   
            </form>
    </div>
    </div>
   