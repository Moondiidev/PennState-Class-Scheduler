

const Filter = function (props) {

    const TYPE_FILTER = "typeFilter";

    const filterSettings = {
        typeFilter: [],
    };


    const runFilter = (courses) => {


        courses.forEach((course) => {
            let inFilter = true;

            if (inFilter && filterSettings.typeFilter.indexOf(course.type) > 0) {
                
            }

        })

    }


    const setTypeFilter = (params) => {

        filterSettings.typeFilter = params;




    }
}