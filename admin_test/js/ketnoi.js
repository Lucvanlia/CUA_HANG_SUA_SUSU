$(document).ready(function() {
    $('#search_diadiem').on("keyup", function(){
            var search = $(this).val();
           // alert("search");
          if(search != '')
          {
            $.ajax({
            method: 'POST',
            url:'modul/qlydiadiem/xly.php',
            data:{name:search},
            success: function(reponsive)
            {
                    $('#searchkq').html(reponsive);
            }
        });
          } else {
            $.ajax({
                success: function(reponsive)
            {
                    location.reload();  
                   
            }
                });
          }
  
        });
        document.getElementById("search").focus();
  
          });
  //=========================Tìm kiếm nhanh chất liệu ==============================================
  $(document).ready(function() {
      $('#search_chatlieu').on("keyup", function(){
              var search = $(this).val();
             // alert("search");
            if(search != '')
            {
              $.ajax({
              method: 'POST',
              url:'modul/qlychatlieu/xly.php',
              data:{name:search},
              success: function(reponsive)
              {
                      $('#searchkq').html(reponsive);
              }
          });
            } else {
              $.ajax({
                  success: function(reponsive)
              {
                      location.reload();  
                     
              }
                  });
            }
  
          });
          document.getElementById("search").focus();
            });
  //==================================kt tiemf kiếm nhanh chất liệu =====================================
  //=========================TÌm kiếm nhanh hãng=======================
  $(document).ready(function() {
      $('#search_hang').on("keyup", function(){
              var search = $(this).val();
             // alert("search");
            if(search != '')
            {
              $.ajax({
              method: 'POST',
              url:'modul/qlyhang/xly.php',
              data:{name:search},
              success: function(reponsive)
              {
                      $('#searchkq').html(reponsive);
              }
          });
            } else {
              $.ajax({
                  success: function(reponsive)
              {
                      location.reload();  
                     
              }
                  });
            }
  
          });
          document.getElementById("search").focus();
  
            });
  //=========================kt TÌm kiếm nhanh hãng=======================
  //=========================TÌm kiếm nhanh xuất xứ=======================
  $(document).ready(function() {
    $('#search_xuatxu').on("keyup", function(){
            var search = $(this).val();
           // alert("search");
          if(search != '')
          {
            $.ajax({
            method: 'POST',
            url:'modul/qlyxuatxu/xly.php',
            data:{name:search},
            success: function(reponsive)
            {
                    $('#searchkq').html(reponsive);
            }
        });
          } else {
            $.ajax({
                success: function(reponsive)
            {
                    location.reload();  
                   
            }
                });
          }
  
        });
        document.getElementById("search").focus();
  
          });
  //=========================kt TÌm kiếm nhanh loại=======================
  $(document).ready(function() {
    $('#search_loai').on("keyup", function(){
            var search = $(this).val();
           // alert("search");
          if(search != '')
          {
            $.ajax({
            method: 'POST',
            url:'modul/qlyloai/xly.php',
            data:{name:search},
            success: function(reponsive)
            {
                    $('#searchkq').html(reponsive);
            }
        });
          } else {
            $.ajax({
                success: function(reponsive)
            {
                    location.reload();  
                   
            }
                });
          }
  
        });
        document.getElementById("search").focus();
  
          });
          $(document).ready(function() {
    $('#search_loai').on("keyup", function(){
            var search = $(this).val();
           // alert("search");
          if(search != '')
          {
            $.ajax({
            method: 'POST',
            url:'modul/qlyloai/xly.php',
            data:{name:search},
            success: function(reponsive)
            {
                    $('#searchkq').html(reponsive);
            }
        });
          } else {
            $.ajax({
                success: function(reponsive)
            {
                    location.reload();  
                   
            }
                });
          }
  
        });
        document.getElementById("search").focus();
  
          });
          //=========================kt TÌm kiếm nhanh địa điểm=======================
          $(document).ready(function() {
            $('#search_diadiem').on("keyup", function(){
                    var search = $(this).val();
                   // alert("search");
                  if(search != '')
                  {
                    $.ajax({
                    method: 'POST',
                    url:'modul/qlydiadiem/xly.php',
                    data:{name:search},
                    success: function(reponsive)
                    {
                            $('#searchkq').html(reponsive);
                    }
                });
                  } else {
                    $.ajax({
                        success: function(reponsive)
                    {
                            location.reload();  
                           
                    }
                        });
                  }
          
                });
                document.getElementById("search").focus();
          
                  });
  //========================================Chọn 1 ck bất kì để hiện ra nút delete=====================================================
            $(function() {
              $("#DesignationTable").on("click", function() {
                  $("#delete").toggle($(this).find(".Organization_Desg_Check_margin:checked").length > 0);
              })
              });
  
  
  //==========================================kt Chọn 1 ck bất kì để hiện ra nút delete===================================================
  
  //==================================Chọn nhiều và bỏ chọn nhiều===========================================================
              function selects(){  
                  var del = document.getElementById('delete');
                  var ele=document.getElementsByName('ckcl[]');  
                  for(var i=0; i<ele.length; i++){  
                      if(ele[i].type=='checkbox')  
                      {
                          ele[i].checked=true;  
                          del.style.display="block";
                      } //kt if 
                       }  // kt for 
                   }// kt funciton
              function deSelect(){  
                  var del = document.getElementById('delete');
                  var ele=document.getElementsByName('ckcl[]');  
                  for(var i=0; i<ele.length; i++){  
                      if(ele[i].type=='checkbox')  
                         {
                              ele[i].checked=false;  
                              del.style.display="none";
                          } //kt if 
                       }  // kt for 
                   }// kt funciton    
  //==================================kt Chọn nhiều và bỏ chọn nhiều===========================================================
  