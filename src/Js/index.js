function setEventDetails(eventDetails) {
    var details = JSON.parse(eventDetails);
    var title = document.getElementById('event-title');
    var desc = document.getElementById('event-detail');
    var date = document.getElementById('event-tanggal');
    var pict = document.getElementById('fotoModal');
    var submitTitle = document.getElementById('namaEventdb');
    pict.setAttribute('src', `../img Event/${details.namaGambar}`);
    title.textContent = details.namaEvent;
    desc.textContent = details.description;
    date.textContent = `${details.tanggalEvent} | ${details.lokasi} | ${details.waktu}`;
    submitTitle.setAttribute('value', `${details.namaEvent}`);
    // console.log(submitTitle.value);
}

$('.dropdownEvent').click(function (e) { 
    // console.log("bekerja");
    e.preventDefault();
    const targetId = $(this).attr('data-target');
    document.querySelector(targetId).scrollIntoView({ behavior: 'smooth' });  
});