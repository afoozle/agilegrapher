/**
 * Agile Graphing Application
 */

var AG = {};

(function() {

    AG.Task = function(taskId, name, description, created, completed) {
        console.log("Task Constructor called with name: "+name+" description: "+description+" created: "+created);
        var self = this;
        self.taskId = ko.observable(taskId);
        self.name = ko.observable(name);
        self.description = ko.observable(description);
        self.created = ko.observable(created);
        self.completed = ko.observable(completed);
        self.status = ko.observable('viewing');


        self.setStatus = function(newStatus) {
            console.log("Setting status: "+newStatus);
            self.status(newStatus);
        }

        self.getStatus = function() {
            console.log("Getting status, returning: "+self.status());
            return self.status();
        }

        self.isStatusViewing = function() {
            return self.status() == 'viewing';
        }

        self.isStatusEditing = function() {
            return self.status() == 'editing';
        }

        self.isStatusSaving = function() {
            return self.status() == 'saving';
        }

        self.isStatusDeleting = function() {
            return self.status() == 'deleting';
        }

        return self;
    };


    AG.TaskDao = function() {
        console.log("TaskDao constructor called");
        var self = this;

        // Persist a task to the service
        self.persist = function(task) {
            console.log("TaskDao::persist");
        }

        // Delete a task from the service
        self.delete = function(task) {
            console.log("TaskDao::delete");
            
            $.ajax({
                type: 'DELETE', url: '/task/'+task.taskId(), dataType: 'json',
                async: false,
                data: {}
            });
        }

        self.loadAll = function(taskCollection) {
            console.log("TaskDao::loadAll");
            
            $.ajax({
                type: 'GET', url: '/task/all', dataType: 'json',
                async: false,
                data: {},
                success: function(data) {
                    $.each(data, function (i, task) {
                        taskCollection.push(new AG.Task(task.task_id, task.name, task.description, task.created, task.completed));
                    });
                }
            });
        }

        return self;
    };

    AG.TasksViewModel = function() {
        console.log("TaskViewModel constructor called");
        var self = this;

        self.tasks = new ko.observableArray();
        var taskDao = new AG.TaskDao();
        taskDao.loadAll(self.tasks);

        // Add a new empty Task
        self.addTask = function() {
            var task = new AG.Task(undefined, "", "", "","");
            task.setStatus("editing");
            self.tasks.push(task);
        }

        self.editTask = function(task) {
            task.setStatus('editing');
        }

        self.saveTask = function(task) {
            task.setStatus('saving');
            taskDao.persist(task);
            // TODO: Handle save failures here
            task.setStatus('viewing');
        }

        // Remove a Task
        self.deleteTask = function(task) {
            task.setStatus('deleting');
            taskDao.delete(task);
            // TODO: Handle delete failures here
            self.tasks.remove(task);
        }

        return self;
    };
})();



// Apply bindings
$(document).ready(function() {

    ko.bindingHandlers.fadeVisible = {
        init: function(element, valueAccessor) {
            // Initially set the element to be instantly visible/hidden depending on the value
            var value = valueAccessor();
            $(element).toggle(ko.utils.unwrapObservable(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
        },
        update: function(element, valueAccessor) {
            // Whenever the value subsequently changes, slowly fade the element in or out
            var value = valueAccessor();
            ko.utils.unwrapObservable(value) ? $(element).fadeIn() : $(element).fadeOut();
        }
    };

    console.log("Applybindings being run");
    ko.applyBindings(new AG.TasksViewModel());
});

