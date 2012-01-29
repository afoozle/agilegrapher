/**
 * Agile Graphing Application
 */

var AG = {};

(function(AG ,$ ,undefined) {

    /**
     * Task Model
     *
     * @param taskId
     * @param name
     * @param description
     * @param created
     * @param completed
     */
    AG.Task = function(taskId, name, description, created, completed) {
        console.log("Task Constructor called with name: "+name+" description: "+description+" created: "+created);

        /**
         * Constructor
         */
        var self = {
            taskId : ko.observable(taskId),
            name : ko.observable(name),
            description : ko.observable(description),
            created : ko.observable(created),
            completed : ko.observable(completed),
            status : ko.observable('viewing')
        };

        /**
         * Set all values in one hit
         *
         * @param taskId
         * @param name
         * @param description
         * @param created
         * @param completed
         */
        self.setValues = function(taskId, name, description, created, completed) {
            if (taskId !== undefined) {
                self.taskId(taskId);
            }
            if (name !== undefined) {
                self.name(name);
            }
            if (description !== undefined) {
                self.description(description);
            }
            if (created !== created) {
                self.created(created);
            }
            if (completed !== completed) {
                self.completed(completed);
            }
        };

        /**
         * Set the status
         *
         * @param newStatus
         */
        self.setStatus = function(newStatus) {
            console.log("Setting status: "+newStatus);
            self.status(newStatus);
        };

        /**
         * Getter for status
         */
        self.getStatus = function() {
            console.log("Getting status, returning: "+self.status());
            return self.status();
        };

        /**
         * Is the status currently set to 'viewing' ?
         */
        self.isStatusViewing = function() {
            return self.status() === 'viewing';
        };

        /**
         * Is the status currently set to 'editing' ?
         */
        self.isStatusEditing = function() {
            return self.status() === 'editing';
        };

        /**
         * Is the status currently set to 'saving' ?
         */
        self.isStatusSaving = function() {
            return self.status() === 'saving';
        };

        /**
         * Is the status currently set to 'deleting' ?
         */
        self.isStatusDeleting = function() {
            return self.status() === 'deleting';
        };

        return self;
    };

    /**
     * Task Data Access Object
     */
    AG.TaskDao = function() {
        console.log("TaskDao constructor called");
        var self = this;

        /**
         * Unwrap a task into a persistable object
         */
        self.unwrapTask = function(task) {
            var persistableTask = {
                name: task.name(),
                description: task.description(),
                created: task.created(),
                completed: task.completed()
            };

            // Add Id if required
            if (task.taskId() !== undefined) {
                persistableTask.taskId = task.taskId();
            }

            return persistableTask;
        };

        /**
         * Persist a task to the service
         * @param task
         * @param taskCollection
         */
        self.persist = function(task, taskCollection) {
            console.log("TaskDao::persist");

            var persistableTask = self.unwrapTask(task);

            // New Task = POST, Updated Task = PUT
            var requestType = 'POST';
            var requestUrl = '/task/';
            if (persistableTask.taskId !== undefined) {
                requestType = 'PUT';
                requestUrl += persistableTask.taskId;
            }

            $.ajax({
                type: requestType, url: requestUrl,
                dataType: 'json',
                async: false,
                data: { task: JSON.stringify(persistableTask) },
                success: function(response) {
                    task.setValues(response.taskId, response.name, response.description, response.created, response.completed);
                }
            });
        };

        /**
         * Remove a task from the service
         * @param task
         */
        self.remove = function(task) {
            console.log("TaskDao::delete");
            
            $.ajax({
                type: 'DELETE', url: '/task/'+task.taskId(), dataType: 'json',
                async: false,
                data: {}
            });
        };

        /**
         * Load all tasks and push them onto the taskCollection
         * @param taskCollection
         */
        self.loadAll = function(taskCollection) {
            console.log("TaskDao::loadAll");
            
            $.ajax({
                type: 'GET', url: '/task/all', dataType: 'json',
                async: true,
                data: {},
                success: function(data) {
                    $.each(data, function (i, task) {
                        taskCollection.push(new AG.Task(task.taskId, task.name, task.description, task.created, task.completed));
                    });
                }
            });
        };

        return self;
    };

    /**
     * ViewModel for tasks
     */
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
        };

        self.editTask = function(task) {
            task.setStatus('editing');
        };

        self.saveTask = function(task) {
            task.setStatus('saving');
            taskDao.persist(task);
            // TODO: Handle save failures here
            task.setStatus('viewing');
        };

        // Remove a Task
        self.deleteTask = function(task) {
            task.setStatus('deleting');
            taskDao.remove(task);
            // TODO: Handle delete failures here
            self.tasks.remove(task);
        };

        return self;
    };
})(window.AG = window.AG || {}, jQuery);



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

