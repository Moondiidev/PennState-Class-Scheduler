<div class="flex flex-wrap -mx-3 mb-6">
    <div class="w-full md:w-3/5 px-3 mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-title">
            Title
        </label>
        <input name="title" value="{{$course->title}}" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-title" type="text" placeholder="Database Philosophy">
    </div>
    <div class="w-full md:w-1/5 px-3">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-abbreviation">
            Abbreviation
        </label>
        <input name="abbreviation" value="{{$course->abbreviation}}" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-abbreviation" type="text" placeholder="CMPEN 556">
    </div>
    <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-credits">
            Credits
        </label>
        <div class="relative">
            <select name="credits" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-credits">
                <option @if($course->credits == 1) selected @endif >1</option>
                <option @if($course->credits == 2) selected @endif >2</option>
                <option @if($course->credits == 3) selected @endif >3</option>
                <option @if($course->credits == 4) selected @endif >4</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
    </div>
</div>
<div class="flex flex-wrap -mx-3 mb-2">
    <div class="w-full md:w-1/5 px-3 mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-semester">
            Semester
        </label>
        <select name="semester[]" multiple class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-semester">
            <option @if($course->semesters->contains(1)) selected @endif value="1">Fall</option>
            <option @if($course->semesters->contains(2)) selected @endif value="2">Spring</option>
            <option @if($course->semesters->contains(3)) selected @endif value="3">Summer</option>
        </select>
    </div>
    <div class="w-full md:w-2/5 px-3 mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-prerequisites">
            Prerequisites
        </label>
        <select name="prerequisites[]" multiple class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-prerequisites">
            <option></option>
            @foreach($courses as $course_p)
            <option @if(in_array($course_p->id, $course->prerequisites ?? [])) selected @endif value="{{$course_p->id}}">{{$course_p->abbreviation}}</option>
            @endforeach
        </select>
    </div>
    <div class="w-full md:w-2/5 px-3 mb-6 md:mb-0">
        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-concurrents">
            Concurrents
        </label>
        <select name="concurrents[]" multiple class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-concurrents">
            <option></option>
            @foreach($courses as $course_c)
            <option @if(in_array($course_c->id, $course->concurrents ?? [])) selected @endif value="{{$course_c->id}}">{{$course_c->abbreviation}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="flex flex-wrap -mx-3 mb-2 mt-4">
    <div class="w-full px-3 mb-6 md:mb-0">

        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-description">
            Description
        </label>

        <textarea name="description" rows="4" cols="20" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-description">{{$course->description}}</textarea>
    </div>
</div>
