var $ = jQuery.noConflict();

function toggleModal() {
  $(".kia__modal").toggleClass("kia__show-modal");
}

function openEmail(request_id, nome, email) {
  $("#email__modal").toggleClass("kia__show-modal");
  $("#email_cus").val(email);
  $("#nome_cus").val(nome);
  $("#request_id_cus").val(request_id);
}

function openFollowUp(id) {
  $("#follow__modal ").toggleClass("kia__show-modal");
  $("#request_id").val(id);

}

function openVisita(id, nome, morada, telefone, email) {
  $("#visita__modal").toggleClass("kia__show-modal");
  $("#contact_request_id").val(id);
  $("#nome").val(nome);
  $("#morada").val(morada);
  $("#telefone").val(telefone);
  $("#email").val(email);
}


$(document).ready(function () {
  $(".kia__close-button").click(function () {
    $(".kia__modal").removeClass("kia__show-modal");
  });
  $(".kia__close_modal").click(function () {
    $(".kia__modal").removeClass("kia__show-modal");
  })
})


$(document).ready(function () {
  function validation_visita_form() {

    var date = $('#data').val();
    var time = $('#time').val();
    var local = $('#local').val();
    var message = $('#visita_form #message').val();
    var validation = true;
    if (!date || date.length < 1) {
      $('#date_error').text('Este campo é obrigatório 1');
      validation = false;
    } else {
      $('#date_error').text("");
    }

    if (!time || time.length < 1) {
      $('#time_error').text('Este campo é obrigatório');
      validation = false;
    } else {
      $('#time_error').text("");
    }
    if (!local || local.length < 1) {
      $('#local_error').text('Este campo é obrigatório');
      validation = false;
    } else {
      $('#local_error').text("");
    }
    if (!message || message.length < 1) {
      $('#message_error').text('Este campo é obrigatório');
      validation = false;
    } else {
      $('#message_error').text("");
    }
    return validation;

  }

  $('#visita_form').submit(function (event) {

      if (validation_visita_form()) {

        var eventname, eventdesc, eventloc, eventuid, startdate, enddate;
        eventname = eventdesc = eventloc = eventuid = startdate = enddate = "NOTHING";

        var nome, morada, telefone, email, s_date, s_time, e_date, e_time;
        nome = morada = telefone = email = s_date = s_time = e_date = e_time = "NOTHING";

        var form = $('#visita_form');

        nome = form.find("input[name='nome']").val();
        morada = form.find("input[name='morada']").val();
        telefone = form.find("input[name='telefone']").val();
        email = form.find("input[name='email']").val();
        s_date = form.find("input[name='data']").val();
        s_time = form.find("input[name='time']").val();


        var localdate = s_date + " " + s_time;
        e_date = moment.utc(localdate).format("MM-DD-YYYY");
        e_time = moment.utc(localdate).format("hh:mm a Z");

        eventname = "Visita ao cliente: " + nome;
        eventdesc = "Visita ao cliente dia " + e_date + " " + e_time + "      //" + nome + " //" + morada + " //" + telefone + " //" + email + " // Notas:" + form.find("textarea[name='message']").val();
        eventloc = form.find("select[name='local']").val();

        var eventUIDN = "";
        for (var i = 0; i < 16; i++) {
          eventUIDN += Math.floor(Math.random() * (10 - 1 + 1) + 1);
        }

        eventuid = eventUIDN;
        startdate = enddate = moment.utc(localdate).format("MM/DD/YYYY hh:mm a");


        var filenametrue = eventname.split(" ").join("_");
        filenametrue = filenametrue.replace(/[_\W]+/g, "_") + ".ics";

        startdate = moment.utc(localdate).format("YYYYMMDD[T]HHmmss");
        enddate = moment.utc(localdate).format("YYYYMMDD[T]HHmmss");

        var icsContent =
          "BEGIN:VCALENDAR\r\nPRODID:-//Microsoft Corporation//Outlook 12.0 MIMEDIR//EN\r\nVERSION:2.0\r\nMETHOD:PUBLISH\r\nX-MS-OLK-FORCEINSPECTOROPEN:TRUE\r\nBEGIN:VEVENT\r\nCLASS:PUBLIC\r\nDESCRIPTION:" +
          eventdesc +
          "\r\nDTEND;VALUE=DATE-TIME:" +
          enddate +
          "\r\nDTSTART;VALUE=DATE-TIME:" +
          startdate +
          "\r\nLOCATION:" +
          eventloc +
          "\r\nPRIORITY:5\r\nSEQUENCE:0\r\nSUMMARY;LANGUAGE=en-us:" +
          eventname +
          "\r\nTRANSP:OPAQUE\r\nUID:" +
          eventuid +
          "\r\nX-MICROSOFT-CDO-BUSYSTATUS:BUSY\r\nX-MICROSOFT-CDO-IMPORTANCE:1\r\nX-MICROSOFT-DISALLOW-COUNTER:FALSE\r\nX-MS-OLK-ALLOWEXTERNCHECK:TRUE\r\nX-MS-OLK-AUTOFILLLOCATION:FALSE\r\nX-MS-OLK-CONFTYPE:0\r\nBEGIN:VALARM\r\nTRIGGER:-PT1440M\r\nACTION:DISPLAY\r\nDESCRIPTION:Reminder\r\nEND:VALARM\r\nEND:VEVENT\r\nEND:VCALENDAR";

        var hiddenDL = document.createElement("a");
        hiddenDL.setAttribute(
          "href",
          "data:text/plain;charset=utf-8," + encodeURIComponent(icsContent)
        );


        hiddenDL.setAttribute("download", filenametrue);
        hiddenDL.setAttribute("target", "_blank");
        hiddenDL.style.display = "none";
        document.body.appendChild(hiddenDL);
        hiddenDL.click();
        document.body.removeChild(hiddenDL);
        event.currentTarget.submit();
      } else {
        return false
      }
    }
  );


  // admin onchange model
  $('#propostas_form #modelo_id').on('change', function () {
    var modelo_id = this.value;
    jQuery.ajax({
      type: "POST",
      url: ajaxurl,
      data: {action: 'kia_get_versao', param: modelo_id}
    }).done(function (msg) {
      $('#propostas_form #versao').html(msg);
    });
  });

  $('#propostas_form #versao').change(function(){
    var selected = $(this).find('option:selected');
    var preco = selected.data('preco');
    $("#preco").val(preco);
    $("#valor_da_proposta").val(preco);
    $("#preco").css('pointer-events','none');
    $("#valor_da_proposta").css('pointer-events','none');
  });

  function valor_da_proposta(type, value) {
    var preco = $("#propostas_form #preco").val();
    if (preco != '' && preco != 'undefined' && preco != 0) {
      var result = '';
      if (type == "desconto_mt") {
        result = preco - value;
      }
      if (type == "desconto") {
        var discount_preco = (value / 100 * preco);
        result = preco - discount_preco;
        $("#propostas_form #desconto_mt").val(discount_preco);
      }
      $("#valor_da_proposta").val(result);
    } else {
      alert('Insira o preço primeiro');
    }

  }

  function reset_propostas_discount() {
    $("#propostas_form #desconto_mt").val('');
    $("#propostas_form #desconto").val('');
    $("#propostas_form #valor_da_proposta").val('');
  }

  $("#propostas_form #preco").bind('keyup mouseup', function () {
    reset_propostas_discount();
  });

  $("#propostas_form #dis_reset").click(function () {
    reset_propostas_discount();
    $("#propostas_form #desconto_mt").val('');
  });
  $("#propostas_form #desconto_mt").bind('keyup mouseup', function () {
    $("#propostas_form #desconto").val('');
    valor_da_proposta("desconto_mt", $(this).val());
    $("#propostas_form #desconto_typo").val('Disconto MT');
  });

  $("#propostas_form #desconto").on('change', function () {
    $("#propostas_form #desconto_mt").val('');
    valor_da_proposta("desconto", $(this).val());
    $("#propostas_form #desconto_typo").val('Disconto');

  });


})


$(document).ready(function () {
	if ($("#kia-request-email-form").length) {
  $("#kia-request-email-form").validate({
    rules: {
      assunto: {
        required: true,
        minlength: 3
      },
    },
    messages: {
      assunto: "Insira um assunto válido",
    }
  });
	}
	
	
	if ($("#kia-request-followup-form").length) {	
  $("#kia-request-followup-form").validate({
    rules: {
      message: {
        required: true,
        minlength: 3
      },
    },
    messages: {
      message: "Escreva alguma mensagem",
    }
  });
	}
	
	
	if ($("#propostas_form").length) {	
  $("#propostas_form").validate({
    rules: {
      tipo_de_interaccao: "required",
      particular_empresa: "required",
      nome_empresa: "required",
      nuit: "required",
      pessoa_de_contacto: "required",
      morada: "required",
      telefone: {
        require_from_group: [1, ".phone-group"],
        digits: true
      },
      email: {
        require_from_group: [1, ".phone-group"],
        email: true
      },
      modelo_id: "required",
      versao: "required",
      preco: {
        required: true,
        min: 1,
        number: true,
      }
    },
    messages: {
      tipo_de_interaccao: "Este campo é obrigatório",
      particular_empresa: "Este campo é obrigatório",
      nome_empresa: "Este campo é obrigatório",
      nuit: "Este campo é obrigatório",
      pessoa_de_contacto: "Este campo é obrigatório",
      morada: "Este campo é obrigatório",
      telefone:{
        digits: "Apenas o número é permitido",
        required: "Este campo é obrigatório",
        require_from_group: "Escreva pelo menos telfone ou e-mail"
      },
      email:{
        required: "Por favor insira um endereço de e-mail válido",
        require_from_group: "Escreva pelo menos telfone ou e-mail"
      },
      modelo_id: "Este campo é obrigatório",
      versao: "Este campo é obrigatório",
      preco: {
        required: "Este campo é obrigatório",
        min: "Deve ser maior que 1",
        number: "Insira um preço válido (por exemplo: 97,34)",
        step: "Insira um múltiplo de 0,01 (por exemplo: 93,23)"
      }
    }
  });
	}


});

