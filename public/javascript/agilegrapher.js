/**
 * Agile Graphing Application
 */

// Class to represent a row in the seat reservations grid
function Task(name, description, created, completed) {
    console.log("Constructor called with name: "+name+" description: "+description+" created: "+created);
    var self = this;
    self.name = ko.observable(name);
    self.description = ko.observable(description);
    self.created = ko.observable(created);
    self.completed = ko.observable(completed);
}

function TaskDao() {
    var self = this;

    self.loadAll = function() {
        var taskCollection = [];
        $.ajax({
            type: 'GET', url: '/task/all', dataType: 'json', async: false,
            data: {},
            success: function(data) {
                $.each(data, function (i, task) {
                    taskCollection[i] = new Task(task.name, task.description, task.created, task.completed);
                });
            }
        });
        return taskCollection;
    }
}

// Overall viewmodel for this screen, along with initial state
function TasksViewModel() {
    var self = this;

    self.tasks = ko.observableArray(new TaskDao().loadAll());

    // Add a new empty Task
    self.addTask = function() {
        self.tasks.push(new Task("", "", "",""));
    }

    // Remove a Task
    self.deleteTask = function(task) {
        self.tasks.remove(task);
    }
}

ko.applyBindings(new TasksViewModel());
