NAME: Michael Lee
EMAIL: michael.y.lee.cs@gmail.com
UID: 904642918

Unfortunately, I do not have anymore late days to use on this project since I decided to do everything on my own and used mine for previous project parts. Because of that, I understand any point deductions that come as penalty for my late submission.

The most difficult part of this project was honestly just trying to do the calculations that are part of task 10. The reason this was difficult was because initially each query I did on the dataframe I created took around 30 minutes, so I wrote the final dataframe to disk as a parquet. However, that took a few hours on its own. 

For Task 1, I loaded the data from the json/csv files and then wrote them as parquets. I have uncommented the original code and commented out the parquet-related lines. 

For Task 7, I originally saved the models after creating them, but had great difficult in figuring out how to load them back in. Since it took maybe less than 5 minutes to calculate each model, I just decided it would be easier to calculate them every time I ran the script.

I didn't run into any OutOfMemory issues when joining the final tables during Tasks 8/9, so these joins are probably not efficient but were able to result in the necessary columns needed to do the calculations. 

I wrote the finalResult dataframe as a parquet and then loaded that to do the final calculations since it reduced query times from ~30 minutes each to less than one minute each. 

Quick Note on analysis.R/analysis.py:
    analysis.R and analysis.py are just p2b_plots.R and p2b_plots.py with some minor modifications and in order to fit the spec. For an unknown reason, I wasn't able to get part 5A to work with the R script, so I used the R script to generate the plots for parts 1-4 and used the python script to generate the plots for part 5A and 5B.
