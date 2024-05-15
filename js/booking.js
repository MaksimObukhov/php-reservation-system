// This part of the code is responsible for the dynamic loading of the schedules
document.getElementById('barber').addEventListener('change', function() {
    const barberId = this.value;
    const scheduleSelect = document.getElementById('schedule');

    scheduleSelect.innerHTML = '<option value="">Select a time slot</option>';

    if (barberId) {
        fetch('get_schedules.php?barber_id=' + barberId)
            .then(response => response.json())
            .then(data => {
                data.forEach(schedule => {
                    const option = document.createElement('option');
                    option.value = schedule.id;
                    option.textContent = `${schedule.date} at ${schedule.time}`;
                    scheduleSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching schedules:', error));
    }
});