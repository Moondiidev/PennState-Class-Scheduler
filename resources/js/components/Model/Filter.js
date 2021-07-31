const filter = function () {

    const COURSE_TYPE = "courseType";
    const COMPLETED = "completed";
    const COURSE_LEVEL = "courseLevel";

    const filterSettings = {
        typeFilter: [],
        completedFilter: true,
        courseLevel: 0
    };

    const getFilterSettings = () => {
        return filterSettings;
    }

    const runFilter = (courses) => {
        console.log("run filter", filterSettings);
        return courses.map((course) => {
            let inFilter = true;

            if (!filterSettings.completedFilter) {
                inFilter = !course.isCompleted;
            }

            if (inFilter && filterSettings.courseLevel > 0) {
                inFilter = (course.level > filterSettings.courseLevel)
            }

            course.inFilter = inFilter;
            return course;
        });

    }

    const setCourseTypeFilter = (params) => {
        filterSettings.typeFilter = params;
    }

    const setCompletedFilter = (params) => {
        filterSettings.completedFilter = params;
        console.log("setCompletedFilter: ", filterSettings);
    }

    const setCourseLevelFilter = (params) => {
        filterSettings.courseLevel = params
    }

    const setFilter = (setting, params) => {
        console.log("setting, params", setting, params)

        switch (setting) {
            case COURSE_TYPE:
                setCourseTypeFilter(params);
                break;
            case COMPLETED: 
                setCompletedFilter(params);
                break;
            case COURSE_LEVEL:
                setCourseLevelFilter(params);
                break;
            default:
                console.log(setting + " is not an implemented implemented filter")
        }
    }

    return {
        getFilterSettings,
        setFilter,
        runFilter,

        COURSE_TYPE,
        COMPLETED,
        COURSE_LEVEL
    }
}

export default filter();