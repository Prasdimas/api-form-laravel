    $(document).ready(function(){
        $('#tahunLahir').datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        });
    });
        $(document).ready(function () {
            $('#skillSet').select2({
                placeholder: 'Pilih Skill',
                ajax: {
                    url: 'http://localhost:8000/api/skills',
                    processResults: function (data) {
                        return {
                            results: data.skills.map(function (skill) {
                                return {
                                    id: skill.id,
                                    text: skill.name
                                };
                            })
                        };
                    }
                }
            });

const jobTitleSelect = document.getElementById('jobTitle');


const placeholderOption = document.createElement('option');
placeholderOption.value = '';
placeholderOption.text = 'Pilih Jabatan';
jobTitleSelect.add(placeholderOption);

axios.get('http://localhost:8000/api/jobs')
    .then(response => {
        const jobTitles = response.data.jobs;

        jobTitles.forEach(job => {
            const option = document.createElement('option');
            option.value = job.id;
            option.text = job.name;
            jobTitleSelect.add(option);
        });
    })
    .catch(error => {
        console.error('Error fetching job titles:', error);
    });



    document.getElementById('applicationForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData(this);
        const job_id = formData.get('jobTitle'); 
        const name = formData.get('namaLengkap');
        const email = formData.get('email');
        const phone = formData.get('telepon');
        const year = formData.get('tahunLahir');
        const skillSet = formData.getAll('skillSet');

        const jsonData = {
            job_id: job_id,
            name: name,
            email: email,
            phone: phone,
            year: year,
            skill_sets: skillSet.map(skill_id => ({ skill_id: skill_id }))
        };
        axios.post('http://127.0.0.1:8000/api/candidates', jsonData)
        .then(response => {
            console.log('Data saved successfully', response.data);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Data saved successfully!',
            });
        })
        .catch(error => {
            if (error.response && error.response.data && error.response.data.message) {
                if (typeof error.response.data.message === 'object') {
                    const messageObject = error.response.data.message;
                    if (messageObject.email) {
                        const emailError = messageObject.email[0]; 
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: `Email: ${emailError}`,
                        });
                    }
    
                    if (messageObject.phone) {
                        const phoneError = messageObject.phone[0]; 
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: `Phone: ${phoneError}`,
                        });
                    }

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error saving data. Please try again.' + error.response.data.message,
                    });
                }
            } else {
                console.error('Error saving data', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error saving data. Please try again.',
                });
            }
        });
    
});
        });