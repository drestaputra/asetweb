$(function() {
  $.ajax({
      url:'<?php echo base_url();?>alamat/get_all_provinsi',       
      dataType: 'json',
      type: 'post',    
      success:function(response){                     
          optionArr = response;
          $('.select2-kabupaten').html("");
          var newOption = ['<option value="" >Pilih Provinsi</option>'];            
          for(var i=0;i<response.length;i++){                
              newOption.push('<option value="'+ response[i]['id'] +'">'+ response[i]['text'] +'</option>');
          }               
          $('.select2-provinsi').select2({
              minimumInputLength: 0,                        
          }).append(newOption);
      }
  })
  $(".select2-provinsi").on('change',function(){        
    $.ajax({
        url:'<?php echo base_url();?>alamat/get_all_kabupaten/'+this.value,       
        dataType: 'json',
        type: 'post',    
        success:function(response){                     
            optionArr = response;
            $('.select2-kabupaten').html("");
            var newOption = ['<option value="" >Pilih Kabupaten</option>'];            
            for(var i=0;i<response.length;i++){                
                newOption.push('<option value="'+ response[i]['id'] +'">'+ response[i]['text'] +'</option>');
            }               
            $('.select2-kabupaten').select2({
                minimumInputLength: 0,                        
            }).append(newOption).select2('open');        
        }
    })
  });
  $(".select2-kabupaten").on('change',function(){        
    $.ajax({
        url:'<?php echo base_url();?>alamat/get_all_kecamatan/'+this.value,       
        dataType: 'json',
        type: 'post',    
        success:function(response){                     
            optionArr = response;
            $('.select2-kecamatan').html("");
            var newOption = ['<option value="" >Pilih Kecamatan</option>'];            
            for(var i=0;i<response.length;i++){                
                newOption.push('<option value="'+ response[i]['id'] +'">'+ response[i]['text'] +'</option>');
            }               
            $('.select2-kecamatan').select2({
                minimumInputLength: 0,                        
            }).append(newOption).select2('open');        
        }
    })
  });
});