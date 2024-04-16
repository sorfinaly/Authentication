document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("studentForm");

    // Load students when the page is loaded
     fetchStudents();

    form.addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission

        // Validate form fields
        const name = document.getElementById("name").value.trim();
        const matricno = document.getElementById("matricno").value.trim();  
        const email = document.getElementById("email").value.trim();
        const homephone = document.getElementById("homephone").value.trim();
        const mobilephone = document.getElementById("mobilephone").value.trim();    
        const curraddress = document.getElementById("curraddress").value.trim();  
        const homeaddress = document.getElementById("homeaddress").value.trim();

        
        // Regex patterns
        const namePattern = /^[A-Za-z\s]+$/;
        const emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
        const phonePattern = /^01\d-\d{7}$/;
        const matricnoPattern = /^\d{7}$/;
        const addressPattern = /^[A-Za-z0-9/\s,\-.]+$/;

        // Validation
        if (!namePattern.test(name)) {
            alert("Please enter a valid first name.");
            return; // Stop form submission
        } 


        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            return;
        }

        if (!phonePattern.test(homephone)) {
            alert("Please enter a valid home phone number.");
            return;
        }

        if (!phonePattern.test(mobilephone)) {
            alert("Please enter a valid mobile phone number.");
            return;
        }

        if (!addressPattern.test(curraddress)) {
            alert("Please enter a valid current address.");
            return;
        }

        if (!addressPattern.test(homeaddress)) {
            alert("Please enter a valid home address.");
            return;
        }

        if (!matricnoPattern.test(matricno)) {  
            alert("Please enter a valid matric number.");
            return;
        }

        // If all validations pass, submit the form
        const formData = new FormData(form);

        fetch('crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data && Array.isArray(data)){
                // console.log(data); // Log the response data (optional)
                fetchStudents(); // Refresh student list after submitting the form
                form.reset();  // Update student list if data is valid
            } else {
                console.error('Invalid data format:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    function fetchStudents() {
        fetch('crud.php', {
            method: 'GET'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const studentList = document.getElementById("studentBody");
            studentList.innerHTML = '';

            data.forEach(student => {
                const studentItem = document.createElement('tr');
                studentItem.innerHTML = `
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.matricno}</td>
                    <td>${student.curraddress}</td>
                    <td>${student.homeaddress}</td>
                    <td>${student.email}</td>
                    <td>${student.mobilephone}</td>
                    <td>${student.homephone}</td>
                    <td>
                        <button onclick="deleteStudent(${student.id})">Delete</button>
                        <button onclick="updateStudent(${student.id})">Update</button>
                    </td>
                `;
                studentList.appendChild(studentItem);
            });
            
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Define deleteStudent in the global scope
    window.deleteStudent = function(id) {
        if (confirm("Are you sure you want to delete this student?")) {
            fetch('crud.php?id=' + id, {
                method: 'DELETE',
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Log the response data (optional)
                 fetchStudents(); // Update student list after deletion
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    };


    window.updateStudent = function(id) {
        // Fetch the student details by ID and pre-fill the form fields
        fetch(`crud.php?id=${id}`, {
            method: 'GET'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Pre-fill the form fields with the student details
            document.getElementById("firstname").value = data.firstname;
            document.getElementById("lastname").value = data.lastname;
            document.getElementById("email").value = data.email;
            document.getElementById("phone").value = data.phone;
            document.getElementById("date").value = data.date;
            document.getElementById("time").value = data.time;
            document.getElementById("guests").value = data.guests;
            document.getElementById("type").value = data.type;

            // Modify the form submission to PUT method for update
            form.removeEventListener("submit", formEventListener);
            form.addEventListener("submit", function(event) {
                // event.preventDefault(); // Prevent default form submission

                // Validate form fields
                // ... (same validation code as before)

                // If all validations pass, submit the form as PUT request for update
                const formData = new FormData(form);

                fetch(`crud.php?id=${id}`, {
                    method: 'PUT',
                    body: formData
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data && Array.isArray(data)){
                         fetchStudents(); // Refresh student list after submitting the form
                        form.reset();  // Update student list if data is valid
                    } else {
                        console.error('Invalid data format:', data);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };

});


