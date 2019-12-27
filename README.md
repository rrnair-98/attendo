#attendo

A GraphQL API for Attendo app

### Plugins/Add ons
1) Lighthouse (GQL Integration) : https://lighthouse-php.com/master/getting-started/tutorial.html2

###Queries

##### Lectures
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
