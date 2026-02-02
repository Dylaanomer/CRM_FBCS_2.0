let inputs = {
  type: "hosting",
  date_start: new Date().toISOString().substr(0, 10),
  date_stop: null,
  interval: 1,
  amount: 1,
  optional: '',
  reminder_id: null,
}

$(document).ready(function() {

})

$("#forms").on('click', 'div', function () {
  inputs.type = $(this).attr('id')
  inputs.optional = ''
  renderForm()
});

$(document).on('input', '.forminput', function() {
  let key = $(this).attr('name')
  let value = $(this).val()

  inputs[key] = value
});

$(document).on('click', '#submit', function() {
  let next_due = new Date(Date.parse(inputs.date_start)),
      dateNow = new Date()

  // if (Date.parse(inputs.date_start) > Date.parse(Date())) {
  //   next_due = inputs.date_start
  // } else {
  //   next_due.setMonth(next_due.getMonth() + Number(inputs.interval))

  // }

  while (next_due < dateNow) next_due.setMonth(next_due.getMonth() + Number(inputs.interval))

  next_due = next_due.toISOString().substr(0, 10)

  jQuery.ajax({
    url: "ajax/save-reminder.php",
    data:'type='+inputs.type
          +"&date_start="+encodeURIComponent(inputs.date_start)
          +"&date_stop="+encodeURIComponent(inputs.date_stop)
          +"&interval="+encodeURIComponent(inputs.interval)
          +"&next_due="+encodeURIComponent(next_due)
          +"&amount="+encodeURIComponent(inputs.amount)
          +"&optional="+encodeURIComponent(inputs.optional)
          +"&customer_id="+encodeURIComponent(customer.customer_id)
          +"&reminder_id="+encodeURIComponent(inputs.reminder_id)
          +"&created_by="+getCookie("name"),
    type: "POST",
  success:function(){
    renderCustomerReminders()
  },
  error:function(err){
    alert(`Kon ${inputs.type} niet opslaan`) //error message
    let res = JSON.parse(err.responseText)
    console.log(res)
  }
  });
});

$(document).on('click', '#delete', function() {
  jQuery.ajax({
    url: "ajax/delete-reminder.php",
    data:'id='+encodeURIComponent(inputs.reminder_id),
    type: "POST",
  success:function(data){
    $("#create #form").html("")
  },
  error:function(err){
    alert(`Kon ${inputs.type} niet opslaan`) //error message
    let res = JSON.parse(err.responseText)
    console.log(res)
  }
  });
});

function renderForm() {
  console.log(inputs)
  $("#forms div").removeClass("selected");
  let form = '<label for="amount"> Klant Naam</label>\
              <input id="amount" class="forminput" type="name" name="amount" value="naam">'

   form += `<label for="optional"> Type PC </label>\
              <input id="optional" class="forminput" type="forminput" name="optional" value="Windows PC">`

   form += `<label for="optional"> Windows Versie </label>\
              <input id="optional" class="forminput" type="forminput" name="optional" value="11">\
              <br></br>`

   form += `    <label><input type="checkbox" name="avastCodeActivation"> Avast code geactiveerd</label>
        <br>
        <small>
        <a href="https://licenties.fbcs.nl" target="_blank" style="color: black; font-weight: bold; text-decoration: none;"> Link Website voor Avast code activatie</a>
        </small>
        <br>
        <label><input type="checkbox" name="energiebeheer"> Energiebeheer ingesteld</label>
        <label><input type="checkbox" name="avastinstellingen"> Avast instellingen gedaan</label>
        <label><input type="checkbox" name="Schijfopslag"> Schijfopslag geoptimaliseerd</label>
        <label><input type="checkbox" name="partitiesnaamSDDHDD"> Partities hernoemd (SSD/HDD)</label>
        <label><input type="checkbox" name="openshell"> Open-Shell geinstalleerd</label>
        <label><input type="checkbox" name="FBCSSupremobureablad"> FBCS Supremobureablad ingesteld</label>
        <label><input type="checkbox" name="Updates"> Alle updates uitgevoerd</label>
        <label><input type="checkbox" name="Wifi6Settings"> Wifi 6 instellingen gedaan</label>
        <label><input type="checkbox" name="DeliveryOptimization"> Delivery Optimization ingesteld</label>
        <label><input type="checkbox" name="VeamAgent"> Veam Agent geinstalleerd</label>
        <label><input type="checkbox" name="SchijfopruimingUitgevoerd"> Schijfopruiming uitgevoerd</label>
        <label><input type="checkbox" name="FBCSOpstartMap"> FBCS Opstart Map ingesteld</label>
        <label><input type="checkbox" name="ChromeFirefoxEdge"> Chrome, Firefox, Edge geinstalleerd</label>
        <label><input type="checkbox" name="CoolerCleaning"> </label>
       <select name="remindertype" id="remindertypeselector"  style="width: 100%; max-width: 300px; padding: 10px 12px; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #ffffff; background-color: #000000; border: 1px solid #ccc; border-radius: 4px; cursor: pointer; outline: none; transition: border-color 0.2s ease;">
          <option value="FB">Floris Bijl</option>
          <option value="LB">Lars Baas</option>
          <option value="NS">Nick Schoemaker</option>
          <option value="MS">Marit Schoemaker</option>
          <option value="RS">Robin Smit</option>
          <option value="HS">Hidde Spaander</option>
          <option value="JL">Jesse Leerdam</option>
          <option value="DO">Dylaan Omer</option>
          <option value="OS">Oleksandr Stratiichuk</option>
          <option value="RJ">Robin Janneman</option>
          <option value="BB">Bernd Broers</option>
          <option value="DB">Dejaay Buis</option>
          <option value="DK">Daan Koster</option>
          <option value="JS">Jort Schuitemaker</option>
</select>
`
  //add options
  if (inputs.type === "hosting") {
    form += `<label for="optional"> <br></br> Totaal aantal uitbreidingen (per GB) </label>\
              <input id="optional" class="forminput" type="number" name="optional" value="0">`
  } else if (inputs.type === "mail") {
    form += `<label for="optional"> Microsoft plan </label>\
              <select id="optional" class="forminput" name="optional">\
                <option val="kiosk"> Exchange Online Kiosk </option>\
                <option val="plan1"> Exchange Online Plan 1 </option>\
                <option val="plan2"> Exchange Online Plan 2 </option>\
                <option val="basic"> Business Basic </option>\
                <option val="standard"> Business Standard </option>\
                <option val="apps"> Apps for Business </option>\
                <option val="visio"> Visio Plan 2 </option>\
                <option val="365personal"> Office 365 Personal 1 PC </option>\
                <option val="365home"> Office 365 Home 6 PCs </option>\
              </select>`
  } else if (inputs.type === "domainname") {
    form += `<label for="optional"> Domein extensie </label>\
              <select id="optional" class="forminput" name="optional">
                <option val="nl"> .nl </option>\
                <option val="com"> .com </option>\
                <option val="eu"> .eu </option>\
                <option val="info"> .info </option>\
                <option val="net"> .net </option>\
                <option val="nu"> .nu </option>\
                <option val="be"> .be </option>\
              </select>`
  } else if (inputs.type === "ssl") {
  } else if (inputs.type === "cloudcare") {
  } else if (inputs.type === "cloudbackup") {
  } else if (inputs.type === "onderhoud") {
    form += `<label for="optional"> Plan </label>\
              <select id="optional" class="forminput" name="optional">
                <option val="basis"> Basis </option>\
                <option val="uitgebreid"> Uitgebreid </option>\
              </select>`
  } else if (inputs.type === "verhuur") {
    form += `<label for="optional"> Apparaat en prijs</label>\
              <input id="optional" class="forminput" type="text" name="optional">`
  } else if (inputs.type === "voip") {
  } else if (inputs.type === "internet") {
    form += `<label for="optional"> Plan </label>\
              <select id="optional" class="forminput" name="optional">
                <option val="fiber"> FTTH </option>\
                <option val="vdsl2"> VDSL2 </option>\
                <option val="4g"> 4G + router </option>\
              </select>`
  }

  //add date_start
  form += `<label for="date_start"> Start datum factuur </label>\
            <input id="date_start" class="forminput" type="date" name="date_start">`

  //add time interval
  if (inputs.type === "hosting" || inputs.type === "mail" || inputs.type === "cloudcare" || inputs.type === "onderhoud" || inputs.type === "cloudbackup" || inputs.type === "verhuur") {
    form += `<label for="interval"> Maanden tussen herinnering </label>\
              <input id="interval" class="forminput" type="number" name="interval">`
  } else if (inputs.type === "domainname" || inputs.type === "ssl") {
    form += `<div class="duration-msg"> 1 jaar </div>`

    inputs.interval = 12
  } else if (inputs.type === "verhuur") {
    form += `<div class="duration-msg"> 1 maand </div>`

    inputs.interval = 1
  }

  //add date_stop and buttons
  form += `<label for="date_stop"> Stop datum </label>\
            <input id="date_stop" class="forminput" type="date" name="date_stop">
            <br></br>\
            <button id="submit"> Opslaan </button> <button id="delete"> Verwijderen </button>`

  $('#create #form').html(form)

  //append extra information for amount
  if (inputs.type === "cloudbackup") {
    $("label#amount").append(" (per 25GB)")
  } else if (inputs.type === "cloudcare" || inputs.type === "onderhoud") {
    $("label#amount").append(" Computers")
  }

  //set the values
  if (inputs.optional !== '') {
    if (inputs.type === "internet" || inputs.type === "onderhoud" || inputs.type === "domainname" || inputs.type === "mail") {
      $(`option:contains("${inputs.optional}")`).attr("selected", true)
    } else if (inputs.type === "verhuur" || inputs.type === "hosting") {
      $(`input[name="optional"]`).val(inputs.optional)
    }
  } else {
    inputs.optional = $("#optional").val()
  }

  $("input[name=date_start]").val(inputs.date_start)
  $("input[name=interval]").val(inputs.interval)
  $("input[name=amount]").val(inputs.amount)

  $("div[id="+inputs.type+"]").addClass("selected")
}


/*
// add options
form += `<label for="optional"> CCLEANER/MBAM/ADW/KRVT/Hitman Pro </label>
            <input id="optional" class="forminput" type="checkbox" name="optional" value="0">`

form += `<label for="optional"> Chrome/Edge/Firefox </label>
            <input id="optional" class="forminput" type="checkbox" name="optional" value="0">`
*/
