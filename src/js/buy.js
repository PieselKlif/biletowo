const eventDate = document.getElementById("event-date");
const eventTime = document.getElementById("event-time");
const eventRow = document.getElementById("event-row");
const eventSector = document.getElementById("event-sector");
const ticketType = document.getElementById("ticket-type");
const summaryTable = document.getElementById("summary-table");
const seatSelector = document.getElementById("seat-selector");
const fname = document.getElementById("fname");
const lname = document.getElementById("lname");
const email = document.getElementById("email");
const name = document.getElementById("name");
const date = document.getElementById("date");
const time = document.getElementById("time");
const submit = document.getElementById("submit");

const ticket = {
	'time' : 0,
	'tickets': [],
	'seats': []
};

let price = {};
let selectedPrice = 0;

let selectedDate = [];

function updateTable() {
	fetch(`/api/table`, {
		method: 'POST',
		headers: {
			'Content_Type': 'application/json'
		},
		body: JSON.stringify(ticket)
	})
		.then(response => response.json())
		.then(data => {
			summaryTable.innerHTML = "";

			data.seats.forEach(element => {
				const row = document.createElement("tr");
				const name = document.createElement("td");
				const type = document.createElement("td");
				const price = document.createElement("td");

				name.innerHTML = `Sektor <b>${element.sector}</b>, Rząd <b>${element.row}</b>, Miejsce <b>${element.seat}</b>`;
				type.innerHTML = `<b>${element.type}</b>`;
				price.innerHTML = `<b>${element.price} zł</b>`;

				row.appendChild(name);
				row.appendChild(type);
				row.appendChild(price);

				summaryTable.appendChild(row);
			});

			data.tickets.forEach(element => {
				const row = document.createElement("tr");
				const name = document.createElement("td");
				const type = document.createElement("td");
				const price = document.createElement("td");

				name.innerHTML = `<b>${element.name}</b>`;
				type.innerHTML = `<b>${element.quantity}x ${element.price} zł</b>`;
				price.innerHTML = `<b>${element.sum} zł</b>`;

				row.appendChild(name);
				row.appendChild(type);
				row.appendChild(price);

				summaryTable.appendChild(row);
			});

			const row = document.createElement("tr");
			const name = document.createElement("td");
			const type = document.createElement("td");
			const price = document.createElement("td");

			name.innerHTML = `<b>Suma</b>`;
			type.innerHTML = ``;
			price.innerHTML = `<b>${data.sum} zł</b>`;

			row.appendChild(name);
			row.appendChild(type);
			row.appendChild(price);

			summaryTable.appendChild(row);
		})
		.catch(error => {
			console.error('Błąd:', error);
		})
}

function updateTime(selectedEvent) {
	const dateParts = eventDate.value.split("-");
	date.innerText = `${dateParts[2]}.${dateParts[1]}.${dateParts[0]}`;

	selectedDate = selectedEvent;
	
	eventTime.innerHTML = "";
	if (selectedEvent) {
		selectedEvent.event_times.forEach(time => {
			const option = document.createElement("option");
			option.value = time.id;
			option.innerText = time.time;
			eventTime.appendChild(option);
		});
	}
	
	ticket.time = parseInt(eventTime.value);

	time.innerText = selectedDate.event_times.find(event => event.id === parseInt(eventTime.value)).time;
}

eventTime.addEventListener("change", (e) => {
	ticket.time = parseInt(e.target.value);
	ticket.seats = [];	
	updateSeats(eventRow.value);
	time.innerText = selectedDate.event_times.find(event => event.id === parseInt(eventTime.value)).time;
	updateTable();
});

function updateTickets(sectorid) {
	fetch(`/api/tickets/type/event/${eid}/sector/${sectorid}`)
		.then(response => {
			if (!response.ok) {
				return response.json().then(errorData => {
						throw new Error(errorData.error);
				});
			}
			return response.json();
		})
		.then(data => {
			ticketType.innerHTML = "";
			price = data;
			selectedPrice = 0;

			data.forEach((element, i) => {
				const button = document.createElement("button");

				if (i == 0) {
					button.classList.add("selected-ticket");
				}

				button.innerHTML = `${element.name}<br>${element.price} zł`;
				ticketType.appendChild(button);

				button.addEventListener("click", (e) => {
					document.querySelector(".selected-ticket").classList.remove("selected-ticket");
					e.target.classList.add("selected-ticket");

					selectedPrice = i;
				});
			});
		})
		.catch(error => {
			console.error('Błąd:', error.message);
		});
}

function updateSeats(rowid) {
	fetch(`/api/venue/row/${rowid}/time/${ticket.time}`)
		.then(response => {
			if (!response.ok) {
				return response.json().then(errorData => {
						throw new Error(errorData.error);
				});
			}
			return response.json();
		})
		.then(data => {
			seatSelector.innerHTML = "";

			data.forEach(element => {
				const button = document.createElement("button");
				button.classList.add("seat");
				button.innerText = element.name;

				if (element.aviable == false){
					button.classList.add("occupied");
					button.tabIndex = -1;
				}

				const seat = ticket.seats.find(seat => seat.id === element.id);

				if (seat) {
					const pr = price.find(price => price.id === seat.type);

					button.style = `background: ${pr.color_hex};`;
					button.classList.add("selected");
				}

				seatSelector.appendChild(button);

				if (element.aviable == true){
					button.addEventListener("click", (e) => {
						if (e.target.classList.contains("selected")){
							e.target.classList.remove("selected");
							e.target.style = "";

							ticket.seats = ticket.seats.filter(seat => seat.id !== element.id);
						} else {
							e.target.classList.add("selected");
							e.target.style = `background: ${price[selectedPrice].color_hex};`;

							const seat = {
								'id': element.id,
								'type': price[selectedPrice].id
							};

							ticket.seats.push(seat);
						}

						updateTable();
					});
				}
			});
		})
		.catch(error => {
			console.error('Błąd:', error.message);
		});
}

function updateRows(sectorid) {
	fetch(`/api/venue/sector/${sectorid}/rows`)
		.then(response => {
			if (!response.ok) {
				return response.json().then(errorData => {
						throw new Error(errorData.error);
				});
			}
			return response.json();
		})
		.then(data => {
			eventRow.innerHTML = "";

			data.forEach(element => {
				const option = document.createElement("option");
				option.innerText = element.name;
				option.value = element.id;

				eventRow.appendChild(option);
			});

			updateSeats(eventRow.value);

			eventRow.addEventListener("change", (e) => {
				updateSeats(e.target.value);
			});
		})
		.catch(error => {
			console.error('Błąd:', error.message);
		});
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
			updateSeats(eventRow.value);
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	});

fetch(`/api/event/${eid}/tickets`)
	.then(response => {
		if (!response.ok) {
			return response.json().then(errorData => {
					throw new Error(errorData.error);
			});
		}
		return response.json();
	})
	.then(data => {
		data.tickets.forEach(element => {
			const tic = {"id": element.id, "quantity": 0};

			ticket.tickets.push(tic);

			const mainDiv = document.createElement("div");
			const name = document.createElement("h2");
			const sDiv = document.createElement("div");
			const price = document.createElement("h3");
			const minus = document.createElement("button");
			const num = document.createElement("span");
			const add = document.createElement("button");

			mainDiv.classList.add("ticket");
			minus.classList.add("minus");
			num.classList.add("num");
			add.classList.add("add");
			name.innerText = element.name;
			price.innerText = `${element.price} zł`;
			minus.innerText = "-";
			num.innerText = "0";
			add.innerText = "+";
			sDiv.appendChild(price);
			sDiv.appendChild(minus);
			sDiv.appendChild(num);
			sDiv.appendChild(add);
			mainDiv.appendChild(name);
			mainDiv.appendChild(sDiv);
			document.getElementById("tickets").appendChild(mainDiv);

			add.addEventListener("click", () => {
				const ticketU = ticket.tickets.find(ticket => ticket.id === element.id);

				if (ticketU) {
					ticketU.quantity += 1;

					num.innerText = ticketU.quantity;
					updateTable();
				}
			});

			minus.addEventListener("click", () => {
				const ticketU = ticket.tickets.find(ticket => ticket.id === element.id);

				if (ticketU && ticketU.quantity > 0) {
					ticketU.quantity -= 1;

					num.innerText = ticketU.quantity;
					updateTable();
				}
			});
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	})
	
fetch(`/api/event/${eid}/sectors`)
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
			option.innerText = element.name;
			option.value = element.id;
			eventSector.appendChild(option);
		});

		updateRows(eventSector.value);
		updateTickets(eventSector.value);
		
		eventSector.addEventListener("change", (e) => {
			updateRows(e.target.value);
			updateTickets(e.target.value);
		});
	})
	.catch(error => {
		console.error('Błąd:', error.message);
	})

fname.addEventListener("input", (e) => {
	name.innerText = `${e.target.value} ${lname.value}`;
})

lname.addEventListener("input", (e) => {
	name.innerText = `${fname.value} ${e.target.value}`;
})

submit.addEventListener("click", () => {
	ticket.email = email.value;
	ticket.fname = fname.value;
	ticket.lname = lname.value;
	ticket.eid = eid;

	fetch(`/api/buy/ticket`, {
		method: 'POST',
		headers: {
			'Content_Type': 'application/json'
		},
		body: JSON.stringify(ticket)
	})
		.then(res => res.json())
		.then(data => {
			if (data.success == true) {
				window.location.href = "/buy/final";
			} else {
				document.getElementById("info").innerText = data.message;
			}
		})
		.catch(error => {
			console.error('Błąd:', error.message);
		})
});