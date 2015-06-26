$(document).ready(function() {

    var pathArray = window.location.pathname.split( '/' );
    fecha = pathArray[4];//3 en produccion    

    numeroPaginas = $('#numeroPaginas').val();
    $('#paginacion').bootpag({
    total: numeroPaginas,
    page: 1,
    maxVisible: 10
    }).on('page', function(event, num){
        //Contenido ajax.
        $.ajax({
            type: "post",
            url: base_url + "reportes/filtrar_ausentismos",
            cache: false,
            data:{
                page_num: num,
                fecha: fecha
            },
            success: function(response){
                $('#destino_resultado').html("");
                
                var obj = JSON.parse(response);
                if(obj.length > 0){
                    try{
                        var items = [];
                        $.each(obj, function(i,val){
                            cadena = '<tr>'+'<td>'+ val.nombre +'</td>'+'<td>'+ val.dni +'</td>'+'<td>'+ val.lu +'</td>'+'<td>'+ val.facultad +'</td>'+'<td>'+ val.categoria +'</td>'+'</tr>';
                            items.push(cadena);
                        });
                        $('#destino_resultado').append.apply($('#destino_resultado'), items);
                    }catch(e) {
                        console.log('error en el try/catch');
                    }
                }else{
                    $('#destino_resultado').html($('<tr/>').text(" No Se encontraron registros"));
                }
                
            },
            error: function(){
                console.log('error en la respuesta');
            }

        });
    });
});
