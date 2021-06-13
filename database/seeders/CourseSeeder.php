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
            Course::where("abbreviation", "CMPEN 270")->first()->id,
            Course::where("abbreviation", "CMPSC 121")->first()->id],
        ]);
        Course::where("abbreviation", "CMPSC 122")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPSC 121")->first()->id]]);
        Course::where("abbreviation", "CMPSC 360")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPSC 122")->first()->id]]);
        Course::where("abbreviation", "CMPSC 461")->first()->update(["prerequisites" => [
            Course::where("abbreviation", "SWENG 311")->first()->id,
            Course::where("abbreviation", "CMPSC 360")->first()->id],
        ]);
        Course::where("abbreviation", "CMPSC 465")->first()->update(["prerequisites" => [
            Course::where("abbreviation", "CMPSC 122")->first()->id,
            Course::where("abbreviation", "CMPSC 360")->first()->id],
        ]);
        Course::where("abbreviation", "ENGL 202C")->first()->update(["prerequisites" => [Course::where("abbreviation", "ENGL 15")->first()->id]]);
        Course::where("abbreviation", "MATH 26")->first()->update(["prerequisites" => [Course::where("abbreviation", "MATH 22")->first()->id]]);
        Course::where("abbreviation", "MATH 141")->first()->update(["prerequisites" => [
            Course::where("abbreviation", "MATH 22")->first()->id,
            Course::where("abbreviation", "MATH 26")->first()->id],
        ]);
        Course::where("abbreviation", "MATH 220")->first()->update(["prerequisites" => [Course::where("abbreviation", "MATH 140")->first()->id]]);
        Course::where("abbreviation", "MATH 250")->first()->update(["prerequisites" => [Course::where("abbreviation", "MATH 141")->first()->id]]);
        Course::where("abbreviation", "MGMT 301")->first()->update(["prerequisites" => [
            Course::where("abbreviation", "ENGL 15")->first()->id,
            Course::where("abbreviation", "ECON 102")->first()->id,
            Course::where("abbreviation", "MATH 22")->first()->id,
            Course::where("abbreviation", "ECON 102")->first()->id,],
        ]);
        Course::where("abbreviation", "PHYS 212")->first()->update(["prerequisites" => [
            Course::where("abbreviation", "MATH 140")->first()->id,
            Course::where("abbreviation", "PHYS 211")->first()->id],
        ]);
        Course::where("abbreviation", "STAT 318")->first()->update(["prerequisites" => [Course::where("abbreviation", "MATH 141")->first()->id]]);
        Course::where("abbreviation", "SWENG 311")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPSC 122")->first()->id]]);
        Course::where("abbreviation", "SWENG 411")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPSC 122")->first()->id]]);
        Course::where("abbreviation", "SWENG 421")->first()->update(["prerequisites" => [Course::where("abbreviation", "SWENG 411")->first()->id]]);
        Course::where("abbreviation", "SWENG 431")->first()->update(["prerequisites" => [
            Course::where("abbreviation", "SWENG 411")->first()->id,
            Course::where("abbreviation", "STAT 318")->first()->id],
        ]);
        Course::where("abbreviation", "SWENG 452")->first()->update(["prerequisites" => [Course::where("abbreviation", "CMPEN 441")->first()->id]]);
        Course::where("abbreviation", "SWENG 480")->first()->update(["prerequisites" => [Course::where("abbreviation", "SWENG 431")->first()->id]]);
        Course::where("abbreviation", "SWENG 481")->first()->update(["prerequisites" => [Course::where("abbreviation", "SWENG 480")->first()->id]]);


    }

    private function getCourses()
    {
        return
            [

                ["title" => "Rhetoric and Composition",
                 "abbreviation" => "ENGL 15",
                 'type' => "ENGL",
                 "description" => "Intensive, rhetorically based experience in reading and writing that will
                 prepare you both to understand the communications that surround you and to succeed in your
                 own communication efforts.",
                 "credits" => 3,
                ],

                ["title" => "College Algebra II",
                 "abbreviation" => "MATH 22",
                 'type' => "MATH",
                 "description" => "Relations, functions, graphs; polynomial, rational functions, graphs;
                 word problems; nonlinear inequalities; inverse functions; exponential, logarithmic functions;
                 conic sections; simultaneous equations.",
                 "credits" => 3,
                ],

                ["title" => "Plane Trigonometry",
                 "abbreviation" => "MATH 26",
                 'type' => "MATH",
                 "description" => "Trigonometric functions; solutions of triangles; trigonometric
                 equations; identities.",
                 "credits" => 3,
                ],

                ["title" => "Introductory Microeconomic Analysis and Policy",
                 "abbreviation" => "ECON 102",
                 'type' => "ECON",
                 "description" => "Methods of economic analysis and their use; price determination;
                 theory of the firm; distribution.",
                 "credits" => 3,
                ],

                ["title" => "Introductory Macroeconomic Analysis and Policy",
                 "abbreviation" => "ECON 104",
                 'type' => "ECON",
                 "description" => "National income measurement; aggregate economic models; money and income;
                 policy problems.",
                 "credits" => 3,
                ],
                ["title" => "Electrical Circuits and Power Distribution",
                 "abbreviation" => "EE 211",
                 'type' => "EE",
                 "description" => "D.C. and A.C. circuits, transformers, single and three-phase distribution systems,
                 A.C. motors and generators.",
                 "credits" => 3
                ],

                ["title" => "Chemical Principles I",
                 "abbreviation" => "CHEM 110",
                 'type' => "CHEM",
                 "description" => "Basic concepts and quantitative relations.",
                 "credits" => 3,
                ],

                ["title" => "Experimental Chemistry I",
                 "abbreviation" => "CHEM 111",
                 'type' => "CHEM",
                 "description" => "Introduction to quantitative experimentation in chemistry.",
                 "credits" => 1,
                ],

                ["title" => "Digital Design: Theory and Practice",
                 "abbreviation" => "CMPEN 270",
                 'type' => "CMPEN",
                 "description" => "Introduction to digital systems and their design.",
                 "credits" => 4
                ],

                ["title" => "Microprocessors",
                 "abbreviation" => "CMPEN 351",
                 'type' => "CMPEN",
                 "description" => "Microprocessor architecture; memory system design; assembly language programming;
                interrupts; the stacks and subroutines; memory and I/O inter-facing; serial I/O and data communications;
                microprocessors applications.",
                 "credits" => 3,
                ],

                ["title" => "Operating Systems",
                 "abbreviation" => "CMPEN 441",
                 'type' => "CMPEN",
                 "description" => "Resource management in computer systems.",
                 "credits" => 3
                ],

                ["title" => "Communication Networks",
                 "abbreviation" => "CMPEN 461",
                 'type' => "CMPEN",
                 "description" => "Data transmission, encoding, link control techniques, network architecture,
                 design, protocols, and multiple access.",
                 "credits" => 3
                ],

                ["title" => "Introduction to Programming Techniques",
                 "abbreviation" => "CMPSC 121",
                 'type' => "CMPSC",
                 "description" => "Design and implementation of algorithms. Structured programming. Problem
                solving techniques. Introduction to a high-level language, including arrays, procedures, and recursion.",
                 "credits" => 3
                ],

                ["title" => "Intermediate Programming",
                 "abbreviation" => "CMPSC 122",
                 'type' => "CMPSC",
                 "description" => "Object-oriented programming, recursion, fundamental data structures (including stacks,
                queues, linked lists, hash tables, trees, and graphs), the basics of algorithmic analysis, and an
                introduction to the principles of language translation.",
                 "credits" => 3
                ],

                ["title" => "Discrete Mathematics for Computer Science",
                 "abbreviation" => "CMPSC 360",
                 'type' => "CMPSC",
                 "description" => "Discrete mathematics and foundations for modern computer science.",
                 "credits" => 3
                ],

                ["title" => "Database Management Systems",
                 "abbreviation" => "CMPSC  431W",
                 'type' => "CMPSC",
                 "description" => "Fundamental concepts of programming language design, specifications,
                and implementation; programming language paradigms and features; program verification.",
                 "credits" => 3
                ],

                ["title" => "Programming Language Concepts",
                 "abbreviation" => "CMPSC 461",
                 'type' => "CMPSC",
                 "description" => "Fundamental concepts of programming language design, specifications,
                 and implementation; programming language paradigms and features; program verification.",
                 "credits" => 3
                ],

                ["title" => "Data Structures and Algorithms",
                 "abbreviation" => "CMPSC 465",
                 'type' => "CMPSC",
                 "description" => "Fundamental concepts of computer science: data structures, analysis of
                 algorithms, recursion, trees, sets, graphs, sorting.",
                 "credits" => 3
                ],

                ["title" => "Effective Writing: Technical Writing",
                 "abbreviation" => "ENGL 202C",
                 'type' => "ENGL",
                 "description" => "Writing for students in scientific and technical disciplines.",
                 "credits" => 3
                ],

                ["title" => "Calculus with Analytic Geometry I",
                 "abbreviation" => "MATH 140",
                 'type' => "MATH",
                 "description" => "Functions, limits; analytic geometry; derivatives, differentials, applications;
                 integrals, applications.",
                 "credits" => 4
                ],

                ["title" => "Calculus with Analytic Geometry II",
                 "abbreviation" => "MATH 141",
                 'type' => "MATH",
                 "description" => "Derivatives, integrals, applications; sequences and series; analytic geometry;
                 polar coordinates.",
                 "credits" => 4
                ],

                ["title" => "Matrices",
                 "abbreviation" => "MATH 220",
                 'type' => "MATH",
                 "description" => "Systems of linear equations; matrix algebra; eigenvalues and eigenvectors;
                 linear systems of differential equations.",
                 "credits" => 2
                ],

                ["title" => "Ordinary Differential Equations",
                 "abbreviation" => "MATH 250",
                 'type' => "MATH",
                 "description" => "First- and second-order equations; special functions; Laplace transform solutions;
                 higher order equations.",
                 "credits" => 3
                ],

                ["title" => "Basic Management Concepts",
                 "abbreviation" => "MGMT 301",
                 'type' => "MGMT",
                 "description" => "Study of fundamental principles and processes available to the understanding
                 of management.",
                 "credits" => 3
                ],

                ["title" => "General Physics: Mechanics",
                 "abbreviation" => "PHYS 211",
                 'type' => "PHYS",
                 "description" => "Calculus-based study of the basic concepts of mechanics: motion, force,
                 Newton's laws, energy, collisions, and rotation.",
                 "credits" => 4
                ],

                ["title" => "General Physics: Electricity and Magnetism",
                 "abbreviation" => "PHYS 212",
                 'type' => "PHYS",
                 "description" => "Calculus-based study of the basic concepts of electricity and magnetism.",
                 "credits" => 4
                ],

                ["title" => "Elementary Probability",
                 "abbreviation" => "STAT 318",
                 'type' => "STAT",
                 "description" => "Combinatorial analysis, axioms of probability, conditional probability
                 and independence, discrete and continuous random variables, expectation, limit theorems,
                 additional topics",
                 "credits" => 3
                ],

                ["title" => "Object-Oriented Software Design and Construction",
                 "abbreviation" => "SWENG 311", 'type' => "SWENG",
                 "description" => "Design, documentation, testing, and construction of software using software
                 engineering strategies embodied in object-oriented programming languages.",
                 "credits" => 3
                ],

                ["title" => "Software Engineering",
                 "abbreviation" => "SWENG 411",
                 'type' => "SWENG",
                 "description" => "Software engineering principles including lifecycle, dependability,
                 process modeling, project management, requires specification, design analysis, implementation,
                 testing, and maintenance.",
                 "credits" => 3
                ],

                ["title" => "Software Engineering",
                 "abbreviation" => "SWENG 411",
                 'type' => "SWENG",
                 "description" => "Software engineering principles including lifecycle, dependability,
                 process modeling, project management, requires specification, design analysis, implementation,
                 testing, and maintenance.",
                 "credits" => 3
                ],

                ["title" => "Software Architecture",
                 "abbreviation" => "SWENG 421",
                 'type' => "SWENG",
                 "description" => "The analysis and design of software systems using canonical design patterns.",
                 "credits" => 3
                ],

                ["title" => "Software Verification, Validation, and Testing",
                 "abbreviation" => "SWENG 431",
                 'type' => "SWENG",
                 "description" => "ntroduction to methods of software verification, validation, and testing;
                  mathematical foundations of testing, reliability models; statistical testing.",
                 "credits" => 3
                ],

                ["title" => "Embedded Real Time Systems",
                 "abbreviation" => "SWENG 452",
                 'type' => "SWENG",
                 "description" => "The design and implementation of real time systems.",
                 "credits" => 3
                ],

                ["title" => "Software Engineering Design",
                 "abbreviation" => "SWENG 480",
                 'type' => "SWENG",
                 "description" => "Concepts of engineering ethics, economy, and project management, senior
                 capstone project selection, and technical communication skills.",
                 "credits" => 3
                ],

                ["title" => "Software Engineering Project",
                 "abbreviation" => "SWENG 481",
                 'type' => "SWENG",
                 "description" => "Capstone group design projects in software engineering.",
                 "credits" => 3
                ],
                
            ];
    }
}
