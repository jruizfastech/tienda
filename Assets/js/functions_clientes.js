let tableUsuarios;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){
    tableClientes = $('#tableClientes').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Clientes/getClientes",
            "dataSrc":""
        },
        "columns":[
            {"data":"PER_ID"},
            {"data":"PER_IDENTIFICACION"},
            {"data":"PER_NOMBRE"},
            {"data":"PER_APELLIDOS"},            
            {"data":"PER_EMAIL"},             
            {"data":"PER_TELEFONO"},
            {"data":"options"}
        ],
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary"
            },{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr":"Esportar a Excel",
                "className": "btn btn-success"
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"Esportar a PDF",
                "className": "btn btn-danger"
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"Esportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    if(document.querySelector("#formCliente")){
        let formCliente = document.querySelector("#formCliente");
        formUsuario.onsubmit = function(e) {
            e.preventDefault();
            let strIdentificacion = document.querySelector('#txtIdentificacion').value;
            let strNombre = document.querySelector('#txtNombre').value;
            let strApellido = document.querySelector('#txtApellido').value;
            let strEmail = document.querySelector('#txtEmail').value;
            let intTelefono = document.querySelector('#txtTelefono').value;
            let strNit = document.querySelector('#txtNit').value;
            let strNomFiscal = document.querySelector('#txtNombreFiscal').value;
            let strDirFiscal = document.querySelector('#txtDirFiscal').value;
            let strPassword = document.querySelector('#txtPassword').value;

            if(strIdentificacion == '' || strApellido == '' || strNombre == '' || strEmail == '' || intTelefono == '' || strNit == '' || strNomFiscal == '' || strDirFiscal == '')
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }

            let elementsValid = document.getElementsByClassName("valid");
            for (let i = 0; i < elementsValid.length; i++) { 
                if(elementsValid[i].classList.contains('is-invalid')) { 
                    swal("Atención", "Por favor verifique los campos en rojo." , "error");
                    return false;
                } 
            } 
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Clientes/setCliente'; 
            let formData = new FormData(formCliente);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        if(rowTable == ""){
                            tableClientes.api().ajax.reload();
                        }else{
                           rowTable.cells[1].textContent =  strIdentificacion;
                           rowTable.cells[2].textContent =  strNombre;
                           rowTable.cells[3].textContent =  strApellido;
                           rowTable.cells[4].textContent =  strEmail;
                           rowTable.cells[5].textContent =  intTelefono;
                           rowTable = "";
                        }                        
                        $('#modalFormCliente').modal("hide");
                        formCliente.reset();
                        swal("Clientes", objData.msg ,"success");
                        
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
                return false;                
            }
        }
    } 
},false);

function fntViewInfo(idpersona){    
//    let idpersona = idpersona;    
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Clientes/getCliente/'+idpersona;    
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){        

        if(request.readyState == 4 && request.status == 200){            
            let objData = JSON.parse(request.responseText);              
            if(objData.status)
            {                
                document.querySelector("#celIdentificacion").innerHTML = objData.data.PER_IDENTIFICACION;
                document.querySelector("#celNombre").innerHTML         = objData.data.PER_NOMBRE;
                document.querySelector("#celApellido").innerHTML       = objData.data.PER_APELLIDOS;
                document.querySelector("#celTelefono").innerHTML       = objData.data.PER_TELEFONO;
                document.querySelector("#celEmail").innerHTML          = objData.data.PER_EMAIL;
                document.querySelector("#celIde").innerHTML            = objData.data.PER_NIT;
                document.querySelector("#celNomFiscal").innerHTML      = objData.data.PER_NOMBRE_FISCAL;
                document.querySelector("#celDirFiscal").innerHTML      = objData.data.PER_DIRECCION_FISCAL;
                document.querySelector("#celFechaRegistro").innerHTML  = objData.data.fechaRegistro;                 
                $('#modalViewCliente').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

//PHP SyntaxError: JSON.parse: unexpected end of data at line 2 column 1 of the JSON data
//si sale este error es porque falta en los parametros de la función la parte "element"
function fntEditInfo(element, idpersona){
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML ="Actualizar Cliente";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    //let idpersona = idpersona;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Clientes/getCliente/'+idpersona;
    request.open("GET",ajaxUrl,true);
    request.send();
    
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status){
                document.querySelector("#idUsuario").value         = objData.data.PER_ID;
                document.querySelector("#txtIdentificacion").value = objData.data.PER_IDENTIFICACION;
                document.querySelector("#txtNombre").value         = objData.data.PER_NOMBRE;
                document.querySelector("#txtApellido").value       = objData.data.PER_APELLIDOS;
                document.querySelector("#txtTelefono").value       = objData.data.PER_TELEFONO;
                document.querySelector("#txtEmail").value          = objData.data.PER_EMAIL;
                document.querySelector("#txtNit").value            = objData.data.PER_NIT;
                document.querySelector("#txtNombreFiscal").value   = objData.data.PER_NOMBRE_FISCAL;
                document.querySelector("#txtDirFiscal").value      = objData.data.PER_DIRECCION_FISCAL;
            }
        }
        $('#modalFormCliente').modal('show');
    }
}

function fntDelInfo(idpersona){
    //let idUsuario = idpersona;
    swal({
        title: "Eliminar Cliente",
        text: "¿Realmente quiere eliminar al Cliente?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) 
        {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Clientes/delCliente/';
            let strData = "idUsuario="+idpersona;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableClientes.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

function openModal()
{
    rowTable = "";
    document.querySelector('#idUsuario').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Cliente";
    document.querySelector("#formCliente").reset();
    $('#modalFormCliente').modal('show');
}