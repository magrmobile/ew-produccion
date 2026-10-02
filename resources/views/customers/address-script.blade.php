<script>
document.addEventListener('DOMContentLoaded', function () {
    const department = document.getElementById('departamento');
    const municipality = document.getElementById('municipio');
    const district = document.getElementById('distrito');
    const municipalities = Array.from(municipality.options).slice(1).map(option => option.cloneNode(true));
    const districts = Array.from(district.options).slice(1).map(option => option.cloneNode(true));

    function populate(select, options, matches) {
        const selected = select.value;
        while (select.options.length > 1) select.remove(1);
        options.filter(matches).forEach(option => select.add(option.cloneNode(true)));
        select.value = selected;
        if (select.selectedIndex < 0) select.selectedIndex = 0;
    }

    function filterDistricts() {
        populate(district, districts, option => department.value !== '' && municipality.value !== '' &&
            option.dataset.departamento === department.value && option.dataset.municipio === municipality.value);
    }

    function filterMunicipalities() {
        populate(municipality, municipalities, option => option.dataset.departamento === department.value);
        filterDistricts();
    }

    department.addEventListener('change', function () {
        municipality.value = '';
        district.value = '';
        filterMunicipalities();
    });
    municipality.addEventListener('change', function () {
        district.value = '';
        filterDistricts();
    });
    filterMunicipalities();
});
</script>
