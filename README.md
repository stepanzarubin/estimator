##

##"Estimator" has its goal to become a "Cost Valuation Standard" developers can benefit from while implementing pricing mechanisms for any kind of business.
#####*Any developer, regardless of his level, while following specification, should be able to get to the same result.* 

##Examples of usage
https://github.com/stepanzarubin/estimator-example.git

##Idea
1. "Turing machine" https://en.wikipedia.org/wiki/Turing_machine
   Input data => Algorithm => Output data

##Naming conventions:
1. EO - evaluation object.
2. T - tariff.
3. C - calculator.
4. E - estimate.
5. R - result.


##General Notes:
1. http://graphql.org/ as an option of how to describe data e.g. EvaluationObject
   It has a type system http://graphql.org/learn/schema/
2. Lib may have classes for most used programming languages or use only one, since ideally, pricing built with "Estimator" is a standalone application.
3. JSON to describe data structures
   e.g. the whole eval object class tree can be done in this way
   this will make data human readable

4. Recommend limit nesting depth to avoid complications for both developers and those who reviews.
5. Scaffold application as a starting point for any pricing.
6. There can be different pricing models in general, library should fit the most practical in business.
7. Base code written in JS should allow creation of online calculators and universal code (will not work for non JS projects).
   While switching to JS, architecture changes can become obvious.
   At the same time, using strictly typed language looks like a more solid option.


##Evaluation Object:
1. 


##Service:
1. Should not contain sub services.
2. 


##Adjustments:
1. Adjustment should be determined per specific eval object (e.g. Car, Gas)
2. Main eval object determines subtotals adjustments, may require specific adjustment object (SubtotalAdjustment).


##Calculator:
1. All calculators should take general EvaluationObject and Calculator 
   because for specific object we may want specific adjustment 
   and there can be some base rates which are applicable to multiple calculators.
2. When service/subtotal has an adjustments there should be 3 rows:
    a) calculated service/subtotal amount
    b) adjustment amount/percent
    c) effective service/subtotal amount
3. By default calculator should return effective cost, because in most cases we need only the amount.
4. In order to preserve old calculation formula for past estimates:
   e.g. GasService
   GasNewService extend GasService
   override calculation
   Tariff has to keep used calculators map
   
   This makes possible pricing version comparison, compare costs, resulting booking %.
   
   On the other hand, this can be not practical, since nobody is going to re-estimate old lead with an old tariff, it can be enough to save calculator log and display it for old leads.

5. Result as clean JSON.
6. First log message should provide enough information to follow the calculation.
7. "ConfigObject" can potentially dictate calculator behaviours: enable/disable adjustments etc.

##Tariff:
1. Tariff should not contain sub tariffs (e.g. GasTariff should not include SubTariff).
2. Main tariff specifies order of calculation by listing "services" which allows:
   a) see how calculation will happen while looking on the screen
   b) save JSON properly and will stick to saved services
   c) preserve order of calculation without changing it in the codebase
   
   currently I have:
   going with this for now because its easier to define "services" list rather than worry about ordering the array properly (but services order has to be defined anyway so I am not sure about keeping it like this),
   also this approach allows to keep going with object notation instead of array
   
   cost of this benefits - a bit of a duplication
   
        {
           "services": [
               "gas",
               "spend"
           ],
           "gas": {
               "gasLiterCost": 1
           }
        }
   
   array: makes sure that order will be preserved
   iterating via object properties will not give the same result in different programming languages 100%
   
       [
           "gas" : {
              "gasLiterCost": 1
          },
           "spend"
       ]
       
    todo check how it works
    how will it work with services which do not have tariff? e.g. Spend
   
3. Save per main evaluation object.


Representation:
1. Generate file suitable for tools like:
   http://pandoc.org/
   http://www.sphinx-doc.org/
    
   which will allow to generate any kind of high quality file (PDFs and so on)


## TODO
1. 2 eval objects, is it possible to combine these?
   EvaluationObject
   MainEvaluationObject

what looks better?

    1: current
    {
        "common": {
            "is_electric": 0,
            "tires": {
                "qnt": 4,
                "oneTireSpendIncreasePercent": 0.4
            }
        },
        "gas": {
            "firstAndLastMileGas": 0.4,
            "restMileGas": 0.2
        },
        "spend": {
            "firstAndLastMileSpend": 2,
            "restMileSpend": 1
        }
    }
    
    
    2: more clear in terms of services
    {
        "common": {
            "is_electric": 0,
            "tires": {
                "qnt": 4,
                "oneTireSpendIncreasePercent": 0.4
            }
        },
        "services": [
            "gas": {
                "firstAndLastMileGas": 0.4,
                "restMileGas": 0.2
            },
            "spend": {
                "firstAndLastMileSpend": 2,
                "restMileSpend": 1
            }
        ]
    }

## Saving estimate
1. Save Evaluation object.
2. Save Tariff.
3. Save Calculation Log.
5. Representation can be based on Estimate model, since it has all the information (result does not have EO and T)
6. Once estimate saved (quote sent to a customer), show diff between actual and saved estimate

## Future
1. Evaluation Object and Tariff is enough to implement calculation.
   So additional abstraction layer is the only purpose of adding Classes.
   Objects can be described in JSON format.
