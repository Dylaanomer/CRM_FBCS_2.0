let state = "klantdata";
let info = {};
let customer = {};
let get_values = get_query();
let selectedType = 'alles';
let lastSelectedType = 'alles';
let HTMLcustomerInfo = '<div id=form>\
                    <label for="klant"> Klantnummer </label>\
                    <input id="klant" maxlength="20" list="klant_list" pattern="^[a-zA-Z0-9]+$" name="customer_id">\
                    <datalist id="klant_list1"></datalist>\
                    <label for="klant2"> Klant </label>\
                    <input id="klant3" class="customerform" type="text" name="customer">\
                    <label for="note"> Notitie </label>\
                    <textarea id="note" class="customerform" type="text" name="note"/>\
                    </div>\
                    <label for="note">Herstelpunt Aanmaken</label>\
                    <input type="checkbox" id="herstelpunt" name="herstelpunt" checked>\
                    <label for="note"> Windows 11 25H2</label>\
                    <input type="checkbox" id="windows11" name="windows11" checked>\
                    <label for="avast">Avast Geactiveerd</label>\
                    <input type="checkbox" id="avast" name="avast" checked>\
                    <label for="office">Office 365 Geactiveerd</label>\
                    <input type="checkbox" id="office" name="office" checked>\
                    <label for="antivirus">Energiebeheer ingesteld</label>\
                    <input type="checkbox" id="energie" name="energie" checked>\
                    <label for="backup">Avast Instellingen</label>\
                    <input type="checkbox" id="avast_settings" name="avast_settings" checked>\
                    <label for="cloud">Schijfopslag geoptimaliseerd</label>\
                    <input type="checkbox" id="schijf" name="schijf" checked>\
                    <label for="remote">Partities Hernoemd (SSD/HDD)</label>\
                    <input type="checkbox" id="partities" name="partities" checked>\
                    <label for="email">Open-Shell</label>\
                    <input type="checkbox" id="open-shell" name="open-shell" checked>\
                    <label for="antivirus">FBCS Supremo</label>\
                    <input type="checkbox" id="supremo" name="supremo" checked>\
                    <label for="backup">Delivery Optimization</label>\
                    <input type="checkbox" id="delivery_optimization" name="delivery_optimization" checked>\
                    <label for="cloud">Ongewenste Programmas verwijderd</label>\
                    <input type="checkbox" id="uninstall" name="uninstall" checked>\
                    <label for="remote">Windows Games</label>\
                    <input type="checkbox" id="Games" name="games" checked>\
                    <label for="remote">Gadgets</label>\
                    <input type="checkbox" id="gadgets" name="gadgets" checked>\
                    <label for="remote">Chrome/FireFox/Edge instellen</label>\
                    <input type="text" id="browsers" name="browsers"/>\
                    <div id="customer-reminders"> </div>';






$(document).ready(function() {
  renderDashboard();
  if (get_values.id) {
    renderInfo(get_values.id);
  }
})

$("#states").on('click', '.state', function () {
  if (selectedType !== lastSelectedType || $(this).attr('id') !== state) { //this if statement will prevent unnecessary XHR requests
    $("div[id="+state+"]").removeClass("selected"); //remove selected class
    state = $(this).attr('id');       //update state
    lastSelectedType = selectedType;  //update lastSelectedState
    if (state === "customers") {
      renderCustomer();
    } else {
      renderDashboard();
    }
  }
});

$(document).on('click', '#new-reminder', function() {
  inputs = {
    type: "hosting",
    date_start: new Date().toISOString().substr(0, 10),
    date_stop: null,
    interval: 1,
    amount: 1,
    optional: '',
    reminder_id: null,
  }
  renderForm();
});

$(document).on('click', '.infobutton', function() {
  renderInfo($(this).attr('id'));
});

$(document).on('input', '#customer_id', function() {
  //set customer_id and reset inputs
  customer.customer_id = $(this).val();

  //reset inputs to immediately create a new reminder without clicking the button
  $("#new-reminder").trigger('click');

  //render datalist for customer names/ids
  jQuery.ajax({
    url: "ajax/return-info.php",
    data:'id='+encodeURIComponent(customer.customer_id)
          +'&content_type=klantdata',
    type: "POST",
  success:function(res){
    data = JSON.parse(res);
    let i = 0;

    $("#customer_id_list").html("");

    while (data[i]) {
      $("#customer_id_list").append(`<option value="${data[i].customer_id}"> ${data[i].customer} </option>`);
      i++;
    }
  },
  error:function(err){
    alert(`Kon ${inputs.type} niet opslaan`); //error message
    let res = JSON.parse(err.responseText);
    console.log(res);
  }
  });

  //render customer reminders if id is a match
  renderCustomerReminders();

  //enter customer info if id is a match
  fillCustomerInfo();
});

$(document).on('input', '.customerform', function() {
  let key = $(this).attr('name');
  let value = $(this).val();
  customer[key] = value;

  jQuery.ajax({
    url: "ajax/save-customer.php",
    data:'customer_id='+encodeURIComponent(customer.customer_id)
          +"&customer="+encodeURIComponent(customer.customer)
          +"&note="+encodeURIComponent(customer.note),
    type: "POST",
  success:function(){
  },
  error:function(err){
    alert(`Kon ${state} niet opslaan`); //error message
    let res = JSON.parse(err.responseText);
    console.log(res);
  }
  });
});

$(document).on('click', "#logout", function() {
  console.log("clicked")
  jQuery.ajax({
    url: "ajax/login/logout.php",
    type: "POST",
  success:function(){
    location.reload();
  },
  error:function(err){
    console.log(err);
  }
  });
});

//register a update from #remindertypeselector
$(document).on('input', '#remindertypeselector', () => {
  let value = $('#remindertypeselector :selected').val();
  selectedType = value;
})

function renderDashboard() {
  jQuery.ajax({
    url: "ajax/render-dashboard.php",
    data:'content_type='+encodeURIComponent(state)+'&filter_type='+encodeURIComponent(selectedType),
    type: "POST",
  success:function(res){
    $('#dashboard-content').html(res);
  },
  error:function(err){
    alert(`Kon ${state} niet laden`); //error message
    let res = err.responseText;
    console.log(res);
  }
  });

  $("div[id="+state+"]").addClass("selected");
}

function renderInfo(id) {
  jQuery.ajax({
    url: "ajax/return-info.php",
    data:'id='+id
          +'&content_type=reminder',
    type: "POST",
  success:function(res){
    data = JSON.parse(res);

    inputs.type = data.type;
    inputs.date_start = data.date_start;
    inputs.date_stop = data.date_stop;
    inputs.interval = data.interval;
    inputs.amount = data.amount;
    inputs.optional = data.optional;

    inputs.reminder_id = id;
    if (state !== "customers") {
      customer.customer_id = data.customer_id;
      $("#customers").trigger('click');
      renderCustomer();
    }

    renderForm();
  },
  error:function(err){
    alert(`Kon ${state} niet laden`); //error message
    let res = JSON.parse(err.responseText);
    console.log(res);
  }
  });
}

function renderCustomer() {
  $('#dashboard-content').html(HTMLcustomerInfo);
  renderCustomerReminders();
  //fillCustomerInfo();//
  $("div[id="+state+"]").addClass("selected");
}

function get_query(){
  var url = location.href;
  var qs = url.substring(url.indexOf('?') + 1).split('&');
  for(var i = 0, result = {}; i < qs.length; i++){
      qs[i] = qs[i].split('=');
      result[qs[i][0]] = qs[i][1];
  }
  return result;
}

function renderCustomerReminders() {
  const current_customer = customer.customer_id;
  jQuery.ajax({
    url: "ajax/render-dashboard.php",
    data:'customer_id='+encodeURIComponent(customer.customer_id)
          +'&content_type=customer',
    type: "POST",
  success:function(res){
    if (current_customer === customer.customer_id) $("#customer-reminders").html(res); // do not render if the customer_id has changed in the meantime
  },
  error:function(err){
    let res = JSON.parse(err.responseText);
    console.log(res);
  }
  });
}

function fillCustomerInfo() {
  const current_customer = customer.customer_id;
  jQuery.ajax({
    url: "ajax/return-info.php",
    data:'id='+encodeURIComponent(customer.customer_id)
          +'&content_type=customer',
    type: "POST",
  success:function(res){
    if (current_customer !== customer.customer_id) return; // do not continue rendering

    data = JSON.parse(res);

    //update customer variable
    customer.customer = data.customer;
    customer.note = data.note;

    //enter data in inputs
    $("#customer").val(data.customer);
    $("#note").val(data.note);
  },
  error:function(err){
    alert(`Kon ${inputs.type} niet opslaan`); //error message
    let res = JSON.parse(err.responseText);
    console.log(res);
  }
  });
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}