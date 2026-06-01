Reminder data mapping plan (no code changes)

Goal:
- Planned menu details come from menu_recommendations
- Completed meal details come from food_histories

Per meal slot: breakfast, lunch, dinner

Inputs:
- meal_reminders (user_id, meal_type, reminder_time)
- menu_recommendations (user_id, recommendation_date=today, breakfast_id/lunch_id/dinner_id)
- food_histories (user_id, consumed_date=today, meal_type, food_id + macros via joined Food model)

Derivation:
1) plannedFoodBySlot:
   - breakfast: Food::find(menu_recommendations.breakfast_id)
   - lunch: Food::find(menu_recommendations.lunch_id)
   - dinner: Food::find(menu_recommendations.dinner_id)
2) completedFoodBySlot:
   - query food_histories filtered by meal_type for today; take first
3) status:
   - completed if completedFoodBySlot exists
   - else current if now() >= today reminder_time and now() < next reminder_time (or simply closest future/past)
   - else upcoming

UI should render:
- completed: greyed, show Logged, no buttons
- current: highlight, show macros, show [Konfirmasi Log Menu] + [Ganti Menu]
- upcoming: visible but inactive, show planned menu macros if exists

