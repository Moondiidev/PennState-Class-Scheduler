<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getCourses() as $course) {

            DB::table('courses')->insert([
                'title' => $course['title'],
                'abbreviation' => $course['abbreviation'],
                'type' => $course['type'],
                'description' => $course['description'],
                'credits' => $course['credits'],
                'semester_id' => rand(1, 3),
            ]);
        }


        // prerequisites and concurrents
        Course::where("abbreviation", "CHEM 111")->first()->update(["prerequisites" => [Course::where("abbreviation", "CHEM 110")->first()->id]]);
        Course::where("abbreviation", "CHEM 111")->first()->update(["concurrents" => [Course::where("abbreviation", "CHEM 110")->first()->id]]);
        Course::where("abbreviation", "CMPEN 351")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPEN 270")->first()->id]]);
        Course::where("abbreviation", "CMPEN 441")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPSC 360")->first()->id]]);
        Course::where("abbreviation", "CMPEN 461")->first()->update(["prerequisites" => [
            Course::where("abbreviation", "CMPEN 270")->first()->id,  Course::where("abbreviation", "CMPSC 121")->first()->id],
        ]);
        Course::where("abbreviation", "CMPSC 122")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPSC 121")->first()->id]]);
        Course::where("abbreviation", "CMPSC 360")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPSC 122")->first()->id]]);

    }

    private function getCourses()
    {
        return
            [
                ["title" => "Chemical Principles I", "abbreviation" => "CHEM 110", 'type' => "CHEM",
                 "description" => "Basic concepts and quantitative relations.", "credits" => 3,
                    "prerequisites"
                ],
                ["title" => "Experimental Chemistry I", "abbreviation" => "CHEM 111", 'type' => "CHEM",
                 "description" => "Introduction to quantitative experimentation in chemistry.", "credits" => 1,
                ],
                ["title" => "Digital Design: Theory and Practice", "abbreviation" => "CMPEN 270", 'type' => "CMPEN",
                 "description" => "Introduction to digital systems and their design.", "credits" => 4
                ],
                ["title" => "Microprocessors", "abbreviation" => "CMPEN 351", 'type' => "CMPEN",
                 "description" => "Microprocessor architecture; memory system design; assembly language programming;
                interrupts; the stacks and subroutines; memory and I/O inter-facing; serial I/O and data communications;
                microprocessors applications.", "credits" => 3,
                ],
                ["title" => "Operating Systems", "abbreviation" => "CMPEN 441", 'type' => "CMPEN",
                 "description" => "Resource management in computer systems.", "credits" => 3
                ],
                ["title" => "Communication Networks", "abbreviation" => "CMPEN 461", 'type' => "CMPEN",
                 "description" => "Data transmission, encoding, link control techniques, network architecture,
                design, protocols, and multiple access.", "credits" => 3
                ],
                ["title" => "Introduction to Programming Techniques", "abbreviation" => "CMPSC 121", 'type' => "CMPSC",
                 "description" => "Design and implementation of algorithms. Structured programming. Problem
                solving techniques. Introduction to a high-level language, including arrays, procedures, and recursion.",
                 "credits" => 3
                ],
                ["title" => "Intermediate Programming", "abbreviation" => "CMPSC 122", 'type' => "CMPSC",
                 "description" => "Object-oriented programming, recursion, fundamental data structures (including stacks,
                queues, linked lists, hash tables, trees, and graphs), the basics of algorithmic analysis, and an
                introduction to the principles of language translation.", "credits" => 3
                ],
                ["title" => "Discrete Mathematics for Computer Science", "abbreviation" => "CMPSC 360", 'type' => "CMPSC",
                 "description" => "Discrete mathematics and foundations for modern computer science.", "credits" => 3
                ],
                ["title" => "Database Management Systems", "abbreviation" => "CMPSC  431W", 'type' => "CMPSC",
                 "description" => "Fundamental concepts of programming language design, specifications,
                and implementation; programming language paradigms and features; program verification.",
                 "credits" => 3
                ],

            ];
    }
}
