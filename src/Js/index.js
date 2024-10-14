function setEventDetails(eventDetails) {
    var details = JSON.parse(eventDetails);
    var title = document.getElementById('event-title');
    var desc = document.getElementById('event-detail');
    var pict = document.getElementById('fotoModal');
    pict.setAttribute('src', `../img Event/${details.namaGambar}`);
    title.textContent = details.namaEvent;
    desc.textContent = details.description;
}

$('#dropdownEvent').click(function (e) { 
    // console.log("bekerja");
    e.preventDefault();
    const targetId = $(this).attr('data-target');
    document.querySelector(targetId).scrollIntoView({ behavior: 'smooth' });  
});