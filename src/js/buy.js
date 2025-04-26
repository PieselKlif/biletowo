const eventDate = document.getElementById("event-date");
const eventTime = document.getElementById("event-time");

function updateTime(selectedEvent) {
	eventTime.innerHTML = "";
	if (selectedEvent) {
		selectedEvent.event_times.forEach(time => {
			const option = document.createElement("option");
			const timeParts = time.split(":");
			option.value = time;
			option.innerText = `${timeParts[0]}:${timeParts[1]}`;
			eventTime.appendChild(option);
		});
	}
}

fetch(`/api/event/${eid}/time`)
	.then(response => {
		if (!response.ok) {
			return response.json().then(errorData => {
					throw new Error(errorData.error);
			});
		}
		return response.json();
	})
	.then(data => {
		data.forEach(element => {
			const option = document.createElement("option");
			const dateParts = element.event_date.split("-");
			option.value = element.event_date;
			option.innerText = `${dateParts[2]}.${dateParts[1]}.${dateParts[0]}`;
			eventDate.appendChild(option);
		});

		const selectedDate = eventDate.value;
		const selectedEvent = data.find(event => event.event_date === selectedDate);

		updateTime(selectedEvent);

		eventDate.addEventListener("change", (e) => {
			const selectedDate = e.target.value;
			const selectedEvent = data.find(event => event.event_date === selectedDate);

			updateTime(selectedEvent);
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	});