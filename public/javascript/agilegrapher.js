/**
 * Agile Graphing Application
 */

var AG = {};

(function() {

    AG.Task = function(name, description, created, completed) {
        console.log("Task Constructor called with name: "+name+" description: "+description+" created: "+created);
        var self = this;
        self.name = ko.observable(name);
        self.description = ko.observable(description);
        self.created = ko.observable(created);
        self.completed = ko.observable(completed);
        self.status = ko.observable('viewing');


        self.setStatus = function(newStatus) {
            self.status(newStatus);
            //self.status.value = newStatus;
        }

        self.getStatus = function() {
            console.log("Getting value of status, returning "+self.status());
            return self.status();
        }

        return self;
    };


    AG.TaskDao = function() {
        console.log("TaskDao constructor called");
        var self = this;

        self.loadAll = function() {
            var taskCollection = [];
            $.ajax({
                type: 'GET', url: '/task/all', dataType: 'json', async: false,
                data: {},
                success: function(data) {
                    $.each(data, function (i, task) {
                        taskCollection[i] = new AG.Task(task.name, task.description, task.created, task.completed);
                    });
                }
            });
            return taskCollection;
        }

        return self;
    };

    AG.TasksViewModel = function() {
        console.log("TaskViewModel constructor called");
        var self = this;

        self.tasks = ko.observableArray(new AG.TaskDao().loadAll());

        // Add a new empty Task
        self.addTask = function() {
            var task = new AG.Task("", "", "","");
            task.setStatus("editing");
            self.tasks.push(task);
        }

        self.editTask = function(task) {
            task.setStatus('editing');
        }

        self.saveTask = function(task) {
            task.setStatus('saving');

            task.setStatus('viewing');
        }

        // Remove a Task
        self.deleteTask = function(task) {
            task.setStatus('deleting');
            self.tasks.remove(task);
        }

        return self;
    };
})();



// Apply bindings
$(document).ready(function() {
    console.log("Applybindings being run");
    ko.applyBindings(new AG.TasksViewModel());
});

