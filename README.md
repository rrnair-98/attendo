#attendo

A GraphQL API for Attendo app

### Plugins/Add ons
1) Lighthouse (GQL Integration) : https://lighthouse-php.com/master/getting-started/tutorial.html2

### Lectures
####Queries
Lecture By id
```$xslt
query{
  lecture(id: 1){
    id
    teacher{
      id
    }
    day_of_week
    lecture_number
    subject_name
  }
}
```


Lectures for department
```$xslt
query{
  depLectures(departmentId: 1){
    id
    teacher{
      id
    }
    day_of_week
    lecture_number
    subject_name
  }
}
```

Lecture By department and teacher
```
query{
  depTeacherLectures(departmentId: 1, teacherId: 1){
    id
    teacher{
      id
    }
    day_of_week
    lecture_number
    subject_name
  }
}
```
Lecture By Teacher
```
query{
  teacherLectures(teacherId: 1){
    id
    teacher{
      id
    }
    day_of_week
    lecture_number
    subject_name
  }
}

```
Lecture by department and dayOfWeek

```
query{
  dayOfWeekDepLectures(departmentId: 1, dayOfWeek:2){
    id
    teacher{
      id
    }
    day_of_week
    lecture_number
    subject_name
  }
}
```

####Mutation
Bulk create lectures,
preferably 20 in 1 call.
```$xslt
mutation{
  createLectures(input: 
  	[{
      teacher_id: 2,
      day_of_week: 3,
      department_id:1,
      lecture_number: 1,
      subject_name: "HELLo"
    },
      {
      teacher_id: 2,
      day_of_week: 3,
      department_id:1,
      lecture_number: 2,
      subject_name: "HELLo world"
      }
    ]
  ){
    message
  }
}
```



